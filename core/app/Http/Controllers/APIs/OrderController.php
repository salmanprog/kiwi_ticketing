<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderTickets;
use App\Models\Transaction;
use App\Models\User;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use App\Helpers\OrdersHelper;
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
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en',true);
        $authCode = Helper::GeneralSiteSettings('auth_code_en',true);
        $date = Carbon::today()->toDateString();

        try {
            $store_order =  OrdersHelper::birthDayOrder($request->all());
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
                $order->customerAddress = isset($orderData['customerAddress']) ? $orderData['customerAddress'] : '0';
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
                if($order->type == 'season_pass')
                $get_order = Order::with(['customer','purchases','transaction'])->where('id',$order->id)->first();
                $resource = OrderResource::make($get_order);
                return $this->sendResponse(200, 'Your Order has been successfully created', $resource);
            }

        } catch (\Exception $e) {
             return $this->sendResponse(400, 'Server Error', $e->getMessage());
        }
    }

    public function getBySlug($slug)
    {
        // $order = Order::where('slug',$slug)->first();
        // $order_type =  OrdersHelper::order_types($order->type);
        $get_order = Order::with(['customer','purchases','transaction'])->where('slug',$slug)->first();
        if (!isset($get_order)) {
            return $this->sendResponse(200, 'Order retrive successfully', []);
        }
        $resource = OrderResource::make($get_order);
        return $this->sendResponse(200, 'Order retrive successfully', $resource);
    }
}
