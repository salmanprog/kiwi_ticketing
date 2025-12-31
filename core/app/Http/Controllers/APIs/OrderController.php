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
use App\Models\ApiLog;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use App\Helpers\OrdersHelper;
use App\Helpers\MailHelper;
use Illuminate\Support\Facades\Mail;
use App\Models\Email;
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
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'order_status' => 'required|in:unpaid_order,paid_order',
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
                //$get_order = Http::get($baseUrl.'/Pricing/QueryOrder2?orderId='.$data['data'][0]['orderNumber'].'&authcode='.$authCode);
                $get_order = Http::withOptions([
                    'curl' => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                    ],
                    'verify' => true, // keep true unless SSL fails
                ])
                ->connectTimeout(15)
                ->timeout(60)
                ->retry(3, 2000)
                ->get($baseUrl.'/Pricing/QueryOrder2', [
                    'orderId' => $data['data'][0]['orderNumber'],
                    'authcode' => $authCode,
                ]);
                $get_order = $get_order->json();
                $orderData = $data['data'][0];
                //$get_user = User::where('id',$request->user_id)->first();
                $get_user = User::firstOrCreate(
                    ['email' => $request->email],
                    [
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'phone' => $request->phone,
                        'password' => bcrypt('Test@123'),
                        'permissions_id' => 3,
                        'status' => 0,
                        'created_by' => 1
                    ]
                );
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
                $order->tax = isset($request->tax_amount) ? $request->tax_amount : '0';
                $order->serviceCharges = isset($request->service_fee) ? $request->service_fee : '0';
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
                $order->user_id  = $get_user->id;
                $order->order_status  = $request->order_status;
                $order->save();
               // return $this->sendResponse(200, 'Generate Order', $get_order);

                if (isset($get_order['data']['tickets']) && is_array($get_order['data']['tickets'])) {
                    //foreach ($get_order['data']['tickets'] as $ticket) {
                       $tickets = mapTicketNamesFromApi($get_order['data']['tickets']);
                        foreach ($tickets as $ticket) {  
                        $ordertickets = new OrderTickets;
                        $ordertickets->order_id = $order->id;
                        $ordertickets->visualId = $ticket['visualId'];
                        $ordertickets->childVisualId = $ticket['childVisualId'];
                        $ordertickets->parentVisualId = $ticket['parentVisualId'];
                        $ordertickets->ticketType = $ticket['ticketType'];
                        $ordertickets->ticketSlug = $ticket['ticketSlug'];
                        $ordertickets->description = $ticket['description'];
                        $ordertickets->seat = $ticket['seat'];
                        $ordertickets->original_price = $ticket['price'];
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
                        $ordertickets->coupon_code = isset($request->promoCode) ? $request->promoCode : 'N/A';
                        $ordertickets->ticket_status = 'ticket_unpaid';
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
                //$emailSent = MailHelper::orderConfirmationEmail($get_order,'new_order');
                //$get_order = Order::with(['customer','purchases','transaction'])->where('id',$order->id)->first();
                $resource = OrderResource::make($get_order);
                ApiLog::create([
                    'type' => 'order',
                    'order_number' => $order->slug,
                    'endpoint' => 'order-create',
                    'status' => 'success',
                    'request' => $request->all(),
                    'response' => $resource,
                    'message' => 'Order has been successfully created',
                ]);
                return $this->sendResponse(200, 'Your Order has been successfully created', $resource);
            }

        } catch (\Exception $e) {
            ApiLog::create([
                'type' => 'order',
                'endpoint' => 'order-create',
                'status' => 'failed',
                'request' => $request->all(),
                'response' => ['error' => $e->getMessage()],
                'message' => 'Server Error',
            ]);
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
                //$get_order = Http::get($baseUrl.'/Pricing/QueryOrder2?orderId='.$request->previousOrderNumber.'&authcode='.$authCode);
                $get_order = Http::withOptions([
                    'curl' => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                    ],
                    'verify' => true, // keep true unless SSL fails
                ])
                ->connectTimeout(15)
                ->timeout(60)
                ->retry(3, 2000)
                ->get($baseUrl.'/Pricing/QueryOrder2', [
                    'orderId' => $request->previousOrderNumber,
                    'authcode' => $authCode,
                ]);
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
                    $tickets = mapTicketNamesFromApi($get_order['data']['tickets']);
                    foreach ($tickets as $ticket) {
                        
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
                ApiLog::create([
                    'type' => 'order',
                    'order_number' => $order_new->slug,
                    'endpoint' => 'order-update',
                    'status' => 'success',
                    'request' => $request->all(),
                    'response' => $resource,
                    'message' => 'Order has been successfully updated',
                ]);
                return $this->sendResponse(200, 'Your Order has been successfully updated', $resource);
            }

        } catch (\Exception $e) {
            ApiLog::create([
                'type' => 'order',
                'endpoint' => 'order-update',
                'status' => 'failed',
                'request' => $request->all(),
                'response' => ['error' => $e->getMessage()],
                'message' => 'Server Error',
            ]);
            return $this->sendResponse(400, 'Server Error', $e->getMessage());
        }
    }

    public function OrderPaid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderNumber' => 'required|string',
            'transactionId' => 'nullable|string',
            'order_status' => 'required|in:unpaid_order,paid_order',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }
        try {
            $order_number = strtolower($request->orderNumber);
            $update_order = Order::where('slug',$order_number)->first();
            $update_order->transactionId  = $request->transactionId;
            $update_order->order_status  = $request->order_status;
            $update_order->updated_at  = Carbon::now();
            $update_order->save();

            $update_ticket_status = OrderTickets::where('order_id',$update_order->id)->update(['ticket_status'=>'ticket_paid']);

            $transaction_update = Transaction::where('order_id',$update_order->id)->first();
            $transaction_update->transactionId = $request->transactionId;
            $transaction_update->save();

            $order_type =  OrdersHelper::order_types($update_order->type);
            $get_order = Order::with(['customer','purchases','apply_coupon','transaction',$order_type])->where('id',$update_order->id)->first();
            $emailSent = MailHelper::orderConfirmationEmail($get_order,'new_order');
            if($update_order->type == 'season_pass'){
                //Send Email
                $get_mail_content = Email::where('identifier', 'Season_Pass')
                ->where('status', '1')
                ->first();
                $raw_content = $get_mail_content->content;
                $placeholders = [
                    '{ERROR_INFORMATION}'    => $request->body_content ?? 'No Issue Found'
                ];
                $raw_content = preg_replace('/\{(?:<[^>]+>)*(\w+)(?:<\/[^>]+>)*\}/', '{$1}', $raw_content);
                $parsed_content = str_replace(array_keys($placeholders), array_values($placeholders), $raw_content);
                $email_subject = $get_mail_content->subject;
                $from_email = config('mail.from.address');
                $from_name = 'BolderAdventurePark';
                $to_email = $update_order->email;
                $get_emails = array_map('trim', explode(',', $get_mail_content->to_reciever));
                if (!in_array($to_email, $get_emails)) {
                        $get_emails[] = $to_email;
                    }
                foreach ($get_emails as $email) {
                    Mail::send('emails.template', [
                        'title' => $email_subject,
                        'details' => $parsed_content
                    ], function ($message) use ($email_subject, $email, $from_email, $from_name) {
                        $message->from($from_email, $from_name);
                        $message->to($email);
                        $message->replyTo($from_email, $from_name);
                        $message->subject($email_subject);
                    });
                }
            }
            $resource = OrderResource::make($get_order);
            ApiLog::create([
                'type' => 'order',
                'order_number' => $get_order->slug,
                'endpoint' => 'order-create',
                'status' => 'success',
                'request' => $request->all(),
                'response' => $resource,
                'message' => 'Order has been successfully paid',
            ]);
            return $this->sendResponse(200, 'Your Order has been successfully paid', $resource);

        } catch (\Exception $e) {
            ApiLog::create([
                'type' => 'order',
                'endpoint' => 'order-create',
                'status' => 'failed',
                'request' => $request->all(),
                'response' => ['error' => $e->getMessage()],
                'message' => 'Server Error',
            ]);
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

    public function OrderReCreate($order_number)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en',true);
        $authCode = Helper::GeneralSiteSettings('auth_code_en',true);
        $date = Carbon::today()->toDateString();
        
        try {
                //https://dynamicpricing-api.dynamicpricingbuilder.com/Pricing/LookUpOrder?Search=pro-1325-202515980&authCode=0a27e421-e1e7-4530-80f8-fca5789b79be
                //$get_order = Http::get($baseUrl.'/Pricing/QueryOrder2?orderId='.$order_number.'&authcode='.$authCode);
                $get_order = Http::withOptions([
                    'curl' => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                    ],
                    'verify' => true,
                ])
                ->connectTimeout(15)
                ->timeout(60)
                ->retry(3, 2000)
                ->get($baseUrl.'/Pricing/QueryOrder2', [
                    'orderId' => $order_number,
                    'authcode' => $authCode,
                ]);
                $get_order = $get_order->json();
                //$data = $get_order['data'] ?? [];
                $data = $get_order['data'] ?? [];
                if($data){
                    $get_previous_order = Order::where('slug',$data['orderNumber'])->first();
                    $get_previous_order->parent_order = $get_previous_order->slug;
                    $get_previous_order->auth_code  = Helper::GeneralSiteSettings('auth_code_en',true);
                    $get_previous_order->type = $get_previous_order->type;
                    $get_previous_order->package_id = $get_previous_order->package_id;
                    $get_previous_order->slug  = $order_number;
                    $get_previous_order->firstName  = $get_previous_order->firstName;;
                    $get_previous_order->lastName = $get_previous_order->lastName;;
                    $get_previous_order->email = $get_previous_order->email;
                    $get_previous_order->phone = $get_previous_order->phone;
                    $get_previous_order->orderTotal = $get_previous_order->orderTotal;
                    $get_previous_order->tax = $get_previous_order->tax;
                    $get_previous_order->serviceCharges = $get_previous_order->serviceCharges;
                    $get_previous_order->orderTip  = $get_previous_order->orderTip;
                    $get_previous_order->orderDate  = $get_previous_order->orderDate;
                    $get_previous_order->slotTime = $get_previous_order->slotTime;
                    $get_previous_order->orderSource = $get_previous_order->orderSource;
                    $get_previous_order->posStaffIdentity = $get_previous_order->posStaffIdentity;
                    $get_previous_order->isOrderFraudulent  = $get_previous_order->isOrderFraudulent;
                    $get_previous_order->orderFraudulentTimeStamp  = $get_previous_order->orderFraudulentTimeStamp;
                    $get_previous_order->customerAddress = $get_previous_order->customerAddress;
                    $get_previous_order->promoCode = $get_previous_order->promoCode;
                    $get_previous_order->transactionId = $get_previous_order->transactionId;
                    $get_previous_order->totalOrderRefundedAmount = $get_previous_order->totalOrderRefundedAmount;
                    $get_previous_order->user_id  = $get_previous_order->user_id;
                    $get_previous_order->save();
                   
                    if (isset($data['tickets']) && is_array($data['tickets'])) {
                        $tickets = mapTicketNamesFromApi($data['tickets']);
                        foreach ($tickets as $ticket) {
                            
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
                                $ordertickets->ticket_status = 'ticket_paid';
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
                    $transaction->amount = $data['orderTotal'];
                    $transaction->save();

                    if (isset($get_previous_order->promoCode) && is_numeric($get_previous_order->promoCode)) {
                        $coupons = Coupons::find($get_previous_order->promoCode);
                        
                        if ($coupons->discount_type === 'percentage') {
                            $discount = ($get_previous_order->orderTotal * $coupons->discount) / 100;
                        } elseif ($coupons->discount_type === 'flat_rate') {
                            $discount = $coupons->discount;
                        }
                        $discount = min($discount, $get_previous_order->orderTotal);
                        $finalAmount = $get_previous_order->orderTotal - $discount;
                        
                        $coupons->coupon_use_limit += 1;
                        $coupons->save();

                        $orderCoupon = OrderCoupon::where('order_id',$get_previous_order->id)->where('coupon_id',$coupons->id)->first();;
                        $orderCoupon->order_id = $get_previous_order->id;
                        $orderCoupon->coupon_id = $coupons->id;
                        $orderCoupon->original_amount = $get_previous_order->orderTotal;
                        $orderCoupon->discount_type = $coupons->discount_type;
                        $orderCoupon->discount = $discount;
                        $orderCoupon->final_amount = $finalAmount;
                        $orderCoupon->save();
                    }

                    $order_type =  OrdersHelper::order_types($get_previous_order->type);
                    $get_order = Order::with(['customer','purchases','apply_coupon','transaction',$order_type])->where('id',$get_previous_order->id)->first();
                    //$emailSent = MailHelper::orderConfirmationEmail($get_order,'regenerate_order');
                    $resource = OrderResource::make($get_order);
                    ApiLog::create([
                        'type' => 'order',
                        'order_number' => $get_previous_order->slug,
                        'endpoint' => 'order-update',
                        'status' => 'success',
                        'request' => $order_number,
                        'response' => $resource,
                        'message' => 'Order has been successfully regenerated',
                    ]);
                    return $this->sendResponse(200, 'Your Order has been successfully regenerated', $resource);
                }else{
                    ApiLog::create([
                        'type' => 'order',
                        'endpoint' => 'order-regenerate',
                        'status' => 'failed',
                        'request' => $order_number,
                        'response' => ['error' => 'data object is empty'],
                        'message' => 'Server Error',
                    ]);
                    return $this->sendResponse(400, 'Server Error', 'data object is empty');
                }

        } catch (\Exception $e) {
            ApiLog::create([
                'type' => 'order',
                'endpoint' => 'order-create',
                'status' => 'failed',
                'request' => '',
                'response' => ['error' => $e->getMessage()],
                'message' => 'Server Error',
            ]);
            return $this->sendResponse(400, 'Server Error', $e->getMessage());
        }
    }
}