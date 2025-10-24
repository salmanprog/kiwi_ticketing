<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderTickets;
use App\Models\Transaction;
use App\Models\OrderCoupon;
use App\Models\Coupons;
use App\Models\User;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use App\Helpers\OrdersHelper;
use App\Helpers\MailHelper;
use Helper;

class OrderController extends BaseAPIController
{
    public function OrderCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'isterminalPayment' => 'nullable|boolean',
            'staffDiscount' => 'nullable|numeric',
            'sessionId' => 'nullable|string',
            'orderCreationWithScript' => 'nullable|boolean',
            'isOfficeUse' => 'nullable|boolean',
            'orderSource' => 'nullable|string',
            'posStaffIdentity' => 'nullable|string',
            'dateNightPass' => 'nullable|string',
            'transactionId' => 'nullable|string',
            'saleFormName' => 'nullable|string',
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'totalAmount' => 'required|numeric',
            'purchases' => 'required|array',
            'package_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en',true);
        $authCode = Helper::GeneralSiteSettings('auth_code_en',true);
        $date = Carbon::today()->toDateString();
        try {
            $store_order =  OrdersHelper::generateOrder($request->all());
            $data = $store_order->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] == 1) {
                return $this->sendResponse(400, 'Order Error', ['error' => $data['status']['errorMessage']]);
            }else{
                $get_order = Http::get($baseUrl.'/Pricing/QueryOrder2?orderId='.$data['data'][0]['orderNumber'].'&authcode='.$authCode);
                $get_order = $get_order->json();
                $orderData = $data['data'][0];
                $get_user = User::where('id',$request->user_id)->first();
                $nameParts = explode(' ', trim($get_user->name));
                $order = new Order;
                $order->auth_code  = Helper::GeneralSiteSettings('auth_code_en',true);
                $order->type = $request->type;
                $order->package_id = $request->package_id;
                $order->slug  = $orderData['orderNumber'];
                $order->firstName  = $nameParts[0];
                $order->lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';
                $order->email = $get_user->email;
                $order->phone = $get_user->phone;
                $order->orderTotal = $orderData['orderTotal'];
                $order->tax = isset($orderData['tax']) ? $orderData['tax'] : '0';
                $order->serviceCharges = isset($orderData['serviceCharges']) ? $orderData['serviceCharges'] : '0';
                $order->orderTip  = isset($orderData['orderTip']) ? $orderData['orderTip'] : '0';
                $order->orderDate  = isset($orderData['orderDate']) ? $orderData['orderDate'] : '0';
                $order->slotTime = isset($orderData['slotTime']) ? $orderData['slotTime'] : '0';
                $order->orderSource = isset($orderData['orderSource']) ? $orderData['orderSource'] : '0';
                $order->posStaffIdentity = isset($orderData['posStaffIdentity']) ? $orderData['posStaffIdentity'] : '0';
                $order->isOrderFraudulent  = isset($orderData['isOrderFraudulent']) ? $orderData['isOrderFraudulent'] : '0';
                $order->orderFraudulentTimeStamp  = isset($orderData['orderFraudulentTimeStamp']) ? $orderData['orderFraudulentTimeStamp'] : '0';
                $order->customerAddress = $request->customerAddress;
                $order->promoCode = isset($request->promoCode) ? $request->promoCode : 'N/A';
                $order->transactionId = isset($orderData['transactionId']) ? $orderData['transactionId'] : $request->transactionId;
                $order->totalOrderRefundedAmount = isset($orderData['totalOrderRefundedAmount']) ? $orderData['totalOrderRefundedAmount'] : '0';
                $order->user_id  = $request->user_id;
                $order->save();
               // return $this->sendResponse(200, 'Generate Order', $get_order);

                if (isset($get_order['data']['tickets']) && is_array($get_order['data']['tickets'])) {
                    foreach ($get_order['data']['tickets'] as $ticket) {
                        
                        $ordertickets = new OrderTickets;
                        $ordertickets->order_id = $order->id;
                        $ordertickets->visualId = $ticket['visualId'];
                        $ordertickets->childVisualId = $ticket['childVisualId'];
                        $ordertickets->parentVisualId = $ticket['parentVisualId'];
                        $ordertickets->ticketType = $ticket['ticketType'];
                        $ordertickets->ticketSlug = $ticket['ticketSlug'];
                        $ordertickets->description = $ticket['description'];
                        $ordertickets->seat = $ticket['seat'];
                        $ordertickets->price = $ticket['price'];
                        $ordertickets->ticketDate = $ticket['ticketDate'];
                        $ordertickets->ticketDisplayDate = $ticket['ticketDisplayDate'];
                        $ordertickets->quantity = $ticket['quantity'];
                        $ordertickets->slotTime = $ticket['slotTime'];
                        $ordertickets->isRefundedOrder = $ticket['isRefundedOrder'];
                        $ordertickets->checkInStatus = $ticket['checkInStatus'];
                        $ordertickets->totalRefundedAmount = $ticket['totalRefundedAmount'];
                        $ordertickets->isWavierFormSubmitted = $ticket['isWavierFormSubmitted'];
                        $ordertickets->isQrCodeBurn = $ticket['isQrCodeBurn'];
                        $ordertickets->wavierSubmittedDateTime = $ticket['wavierSubmittedDateTime'];
                        $ordertickets->refundedDateTime = $ticket['refundedDateTime'];
                        $ordertickets->isTicketUpgraded = $ticket['isTicketUpgraded'];
                        $ordertickets->ticketUpgradedFrom = $ticket['ticketUpgradedFrom'];
                        $ordertickets->isSearchParentRecord = $ticket['isSearchParentRecord'];
                        $ordertickets->validUntil = $ticket['validUntil'];
                        $ordertickets->isSeasonPassRenewal = $ticket['isSeasonPassRenewal'];
                        $ordertickets->isSeasonPass = $ticket['isSeasonPass'];
                        $ordertickets->totalOrderRefundedAmount = $ticket['totalOrderRefundedAmount'];
                        $ordertickets->save();
                    }
                }

                $total_item_quantity = OrderTickets::where('order_id',$order->id)->sum('quantity');
                $transaction = new Transaction;
                $transaction->auth_code  = Helper::GeneralSiteSettings('auth_code_en',true);
                $transaction->order_id = $order->id;
                $transaction->transactionId = $order->transactionId;
                $transaction->totalItem = count($request->purchases);
                $transaction->quantity = $total_item_quantity;
                $transaction->transactionDate = $date;
                $transaction->amount = $data['data'][0]['orderTotal'];
                $transaction->save();

                if (isset($request->promoCode) && $request->promoCode > 0) {
                    $coupons = Coupons::find($request->promoCode);
                    
                    if ($coupons->discount_type === 'percentage') {
                        $discount = ($order->orderTotal * $coupons->discount) / 100;
                    } elseif ($coupons->discount_type === 'flat_rate') {
                        $discount = $coupons->discount;
                    }
                    $discount = min($discount, $order->orderTotal);
                    $finalAmount = $order->orderTotal - $discount;
                    
                    $coupons->coupon_use_limit += 1;
                    $coupons->save();

                    $orderCoupon = new OrderCoupon;
                    $orderCoupon->order_id = $order->id;
                    $orderCoupon->coupon_id = $coupons->id;
                    $orderCoupon->original_amount = $order->orderTotal;
                    $orderCoupon->discount_type = $coupons->discount_type;
                    $orderCoupon->discount = $discount;
                    $orderCoupon->final_amount = $finalAmount;
                    $orderCoupon->save();
                }

                $order_type =  OrdersHelper::order_types($order->type);
                $get_order = Order::with(['customer','purchases','apply_coupon','transaction',$order_type])->where('id',$order->id)->first();
                $emailSent = MailHelper::orderConfirmationEmail($get_order,'new_order');
                //$get_order = Order::with(['customer','purchases','transaction'])->where('id',$order->id)->first();
                $resource = OrderResource::make($get_order);
                return $this->sendResponse(200, 'Your Order has been successfully created', $resource);
            }

        } catch (\Exception $e) {
             return $this->sendResponse(400, 'Server Error', $e->getMessage());
        }
    }


    public function OrderUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'previousOrderNumber' => 'required|exists:order,slug',
            'sessionId' => 'nullable|string',
            'transactionId' => 'nullable|string',
            'date' => 'required',
            'ticketChanges' => 'required|array',
            'totalAmount' => 'required|numeric',
            'addonAmount' => 'required|numeric',
            'order_status' => 'required|in:update_order,upgrade_order',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en',true);
        $authCode = Helper::GeneralSiteSettings('auth_code_en',true);
        $date = Carbon::today()->toDateString();

        try {
            $update_order =  OrdersHelper::updateOrUpgradeOrder($request->all());
            
            $data = $update_order->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] == 1) {
                return $this->sendResponse(400, 'Order Error', ['error' => $data['status']['errorMessage']]);
            }else{
            //     print_r($data);
            // die();
                $get_previous_order = Order::where('slug',$request->previousOrderNumber)->first();
                $get_order = Http::get($baseUrl.'/Pricing/QueryOrder2?orderId='.$request->previousOrderNumber.'&authcode='.$authCode);
                $get_order = $get_order->json();
                //$orderData = $data['data'][0];
                $ticket_status = ($request->order_status == 'update_order') ? 'ticket_update' : 'ticket_upgrade';
               
                $get_previous_order->orderTotal  = $request->totalAmount;
                $get_previous_order->order_status  = $request->order_status;
                $get_previous_order->updated_at  = Carbon::now();
                $get_previous_order->save();
                $prefix = ($request->order_status == 'update_order') ? 'upd'.date("y").'_' : 'upg'.date("y").'_';
                $order_number = Order::generateUniqueSlug($prefix . date('Y') . rand(10000, 99999));
                $order_new = new Order;
                $order_new->parent_order = $get_previous_order->slug;
                $order_new->auth_code  = Helper::GeneralSiteSettings('auth_code_en',true);
                $order_new->type = $get_previous_order->type;
                $order_new->package_id = $get_previous_order->package_id;
                $order_new->slug  = $order_number;
                $order_new->firstName  = $get_previous_order->firstName;;
                $order_new->lastName = $get_previous_order->lastName;;
                $order_new->email = $get_previous_order->email;
                $order_new->phone = $get_previous_order->phone;
                $order_new->orderTotal = $request->addonAmount;
                $order_new->tax = $get_previous_order->tax;
                $order_new->serviceCharges = $get_previous_order->serviceCharges;
                $order_new->orderTip  = $get_previous_order->orderTip;
                $order_new->orderDate  = $get_previous_order->orderDate;
                $order_new->slotTime = $get_previous_order->slotTime;
                $order_new->orderSource = $get_previous_order->orderSource;
                $order_new->posStaffIdentity = $get_previous_order->posStaffIdentity;
                $order_new->isOrderFraudulent  = $get_previous_order->isOrderFraudulent;
                $order_new->orderFraudulentTimeStamp  = $get_previous_order->orderFraudulentTimeStamp;
                $order_new->customerAddress = $get_previous_order->customerAddress;
                $order_new->promoCode = $get_previous_order->promoCode;
                $order_new->transactionId = $request->transactionId;
                $order_new->totalOrderRefundedAmount = $get_previous_order->totalOrderRefundedAmount;
                $order_new->user_id  = $get_previous_order->user_id;
                $order_new->save();

                if (isset($get_order['data']['tickets']) && is_array($get_order['data']['tickets'])) {
                    foreach ($get_order['data']['tickets'] as $ticket) {
                        
                        $existing_ticket = OrderTickets::where('order_id', $get_previous_order->id)->where('visualId', $ticket['visualId'])->first();

                        if (!$existing_ticket) {
                            $ordertickets = new OrderTickets;
                            $ordertickets->order_id = $get_previous_order->id;
                            $ordertickets->visualId = $ticket['visualId'];
                            $ordertickets->childVisualId = $ticket['childVisualId'] ?? null;
                            $ordertickets->parentVisualId = $ticket['parentVisualId'] ?? null;
                            $ordertickets->ticketType = $ticket['ticketType'] ?? null;
                            $ordertickets->ticketSlug = $ticket['ticketSlug'] ?? null;
                            $ordertickets->description = $ticket['description'] ?? null;
                            $ordertickets->seat = $ticket['seat'] ?? null;
                            $ordertickets->price = $ticket['price'];
                            $ordertickets->ticketDate = $ticket['ticketDate'] ?? null;
                            $ordertickets->ticketDisplayDate = $ticket['ticketDisplayDate'] ?? null;
                            $ordertickets->quantity = $ticket['quantity'];
                            $ordertickets->slotTime = $ticket['slotTime'] ?? null;
                            $ordertickets->isRefundedOrder = $ticket['isRefundedOrder'] ?? null;
                            $ordertickets->checkInStatus = $ticket['checkInStatus'];
                            $ordertickets->totalRefundedAmount = $ticket['totalRefundedAmount'];
                            $ordertickets->isWavierFormSubmitted = $ticket['isWavierFormSubmitted'] ?? null;
                            $ordertickets->isQrCodeBurn = $ticket['isQrCodeBurn'] ?? null;
                            $ordertickets->wavierSubmittedDateTime = $ticket['wavierSubmittedDateTime'] ?? null;
                            $ordertickets->refundedDateTime = $ticket['refundedDateTime'] ?? null;
                            $ordertickets->isTicketUpgraded = $ticket['isTicketUpgraded'] ?? null;
                            $ordertickets->ticketUpgradedFrom = $ticket['ticketUpgradedFrom'] ?? null;
                            $ordertickets->isSearchParentRecord = $ticket['isSearchParentRecord'] ?? null;
                            $ordertickets->validUntil = $ticket['validUntil'] ?? null;
                            $ordertickets->isSeasonPassRenewal = $ticket['isSeasonPassRenewal'] ?? null;
                            $ordertickets->isSeasonPass = $ticket['isSeasonPass'] ?? null;
                            $ordertickets->totalOrderRefundedAmount = $ticket['totalOrderRefundedAmount'] ?? null;
                            $ordertickets->ticket_status = 'new_ticket';
                            $ordertickets->save();
                            //for new order
                            $orderNewtickets = new OrderTickets;
                            $orderNewtickets->order_id = $order_new->id;
                            $orderNewtickets->visualId = $ticket['visualId'];
                            $orderNewtickets->childVisualId = $ticket['childVisualId'] ?? null;
                            $orderNewtickets->parentVisualId = $ticket['parentVisualId'] ?? null;
                            $orderNewtickets->ticketType = $ticket['ticketType'] ?? null;
                            $orderNewtickets->ticketSlug = $ticket['ticketSlug'] ?? null;
                            $orderNewtickets->description = $ticket['description'] ?? null;
                            $orderNewtickets->seat = $ticket['seat'] ?? null;
                            $orderNewtickets->price = $ticket['price'];
                            $orderNewtickets->ticketDate = $ticket['ticketDate'] ?? null;
                            $orderNewtickets->ticketDisplayDate = $ticket['ticketDisplayDate'] ?? null;
                            $orderNewtickets->quantity = $ticket['quantity'];
                            $orderNewtickets->slotTime = $ticket['slotTime'] ?? null;
                            $orderNewtickets->isRefundedOrder = $ticket['isRefundedOrder'] ?? null;
                            $orderNewtickets->checkInStatus = $ticket['checkInStatus'];
                            $orderNewtickets->totalRefundedAmount = $ticket['totalRefundedAmount'];
                            $orderNewtickets->isWavierFormSubmitted = $ticket['isWavierFormSubmitted'] ?? null;
                            $orderNewtickets->isQrCodeBurn = $ticket['isQrCodeBurn'] ?? null;
                            $orderNewtickets->wavierSubmittedDateTime = $ticket['wavierSubmittedDateTime'] ?? null;
                            $orderNewtickets->refundedDateTime = $ticket['refundedDateTime'] ?? null;
                            $orderNewtickets->isTicketUpgraded = $ticket['isTicketUpgraded'] ?? null;
                            $orderNewtickets->ticketUpgradedFrom = $ticket['ticketUpgradedFrom'] ?? null;
                            $orderNewtickets->isSearchParentRecord = $ticket['isSearchParentRecord'] ?? null;
                            $orderNewtickets->validUntil = $ticket['validUntil'] ?? null;
                            $orderNewtickets->isSeasonPassRenewal = $ticket['isSeasonPassRenewal'] ?? null;
                            $orderNewtickets->isSeasonPass = $ticket['isSeasonPass'] ?? null;
                            $orderNewtickets->totalOrderRefundedAmount = $ticket['totalOrderRefundedAmount'] ?? null;
                            $orderNewtickets->ticket_status = 'new_ticket';
                            $orderNewtickets->save();
                        }
                    }

                    // if (is_array($request->ticketChanges)) {
                    // foreach ($request->ticketChanges as $ticketJson) {
                    //     $ticket = json_decode($ticketJson, true);
                    //         if (is_array($ticket) && !empty($ticket['visualId'])) {
                    //             OrderTickets::where('order_id', $get_previous_order->id)
                    //                 ->where('visualId', $ticket['visualId'])
                    //                 ->update(['ticket_status' => $ticket_status]);
                    //         }
                    //     }
                    // }
                    if (is_array($request->ticketChanges)) {
                        foreach ($request->ticketChanges as $ticket) {
                            if (is_array($ticket) && !empty($ticket['visualId'])) {
                                OrderTickets::where('order_id', $get_previous_order->id)
                                    ->where('visualId', $ticket['visualId'])
                                    ->update(['ticket_status' => $ticket_status]);
                            }
                        }
                    }
                    
                }

                $total_item_quantity = OrderTickets::where('order_id',$get_previous_order->id)->sum('quantity');
                $total_ticket = OrderTickets::where('order_id',$get_previous_order->id)->count();
                $transaction = Transaction::where('order_id',$get_previous_order->id)->first();
                $transaction->auth_code  = Helper::GeneralSiteSettings('auth_code_en',true);
                $transaction->order_id = $get_previous_order->id;
                $transaction->transactionId = $get_previous_order->transactionId;
                $transaction->totalItem = $total_ticket;
                $transaction->quantity = $total_item_quantity;
                $transaction->amount = $data['data'][0]['orderTotal'];
                $transaction->save();
                //for new order
                $total_item_quantity_new = OrderTickets::where('order_id',$order_new->id)->sum('quantity');
                $total_ticket_new = OrderTickets::where('order_id',$order_new->id)->count();
                $transaction_new = new Transaction;
                $transaction_new->auth_code  = Helper::GeneralSiteSettings('auth_code_en',true);
                $transaction_new->order_id = $order_new->id;
                $transaction_new->transactionId = $order_new->transactionId;
                $transaction_new->totalItem = $total_ticket_new;
                $transaction_new->quantity = $total_item_quantity_new;
                $transaction_new->amount = $request->addonAmount;
                $transaction_new->save();

                $order_type =  OrdersHelper::order_types($order_new->type);
                $get_order = Order::with(['customer','purchases','apply_coupon','transaction',$order_type])->where('id',$order_new->id)->first();
                //$get_order = Order::with(['customer','purchases','transaction'])->where('id',$order->id)->first();
                $emailSent = MailHelper::orderConfirmationEmail($get_order,'update_order');
                $resource = OrderResource::make($get_order);
                return $this->sendResponse(200, 'Your Order has been successfully updated', $resource);
            }

        } catch (\Exception $e) {
             return $this->sendResponse(400, 'Server Error', $e->getMessage());
        }
    }

    public function getBySlug($slug)
    {
        $order = Order::where('slug',$slug)->first();
        $order_type =  OrdersHelper::order_types($order->type);
        $get_order = Order::with(['customer','purchases','apply_coupon','transaction',$order_type])->where('slug',$slug)->first();
        if (!isset($get_order)) {
            return $this->sendResponse(200, 'Order retrive successfully', []);
        }
        $resource = OrderResource::make($get_order);
        return $this->sendResponse(200, 'Order retrive successfully', $resource);
    }
}
