<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Http\Resources\OrderResource;
use Carbon\Carbon;
use App\Helpers\OrdersHelper;
use Helper;

class OrderController extends BaseAPIController
{

    public function ticketHold(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticketType' => 'required|string|max:255',
            'quantity' => 'required|integer|max:255',
            'seat' => 'required|integer|max:255',
            'date' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en',true);
        $authCode = Helper::GeneralSiteSettings('auth_code_en',true);
        $date = Carbon::today()->toDateString();

        try {
            $response = Http::post($baseUrl.'/Pricing/TicketHold?authcode='.$authCode.'&date='.$request->date, [
                'SessionId' => '',
                'TicketHoldItem' => [
                    [
                        'ticketType' => $request->ticketType,
                        'quantity' => $request->quantity,
                        'seat' => $request->seat
                    ]
                ]
            ]);
            if ($response->successful()) {
                $apiData = $response->json();
                $sessionId = $apiData['sessionId'] ?? 0;
                $tickets = $apiData['data'] ?? [];
                $tickets = collect($tickets)->map(function ($ticket) use ($sessionId) {
                    $ticket['sessionId'] = $sessionId;
                    return $ticket;
                })->all();
            }
            if(count($tickets) > 0){
                return $this->sendResponse(200, 'Ticket hold successfully', $tickets);
            }else{
                return $this->sendResponse(400, 'Validation Error', ['seat' => ['No ticket available for this date occupancy is full']]);
            }

        } catch (\Exception $e) {
             return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }

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
            
            $order = new Order;
            $order->auth_code  = Helper::GeneralSiteSettings('auth_code_en',true);
            $order->type = $request->type;
            $order->isterminalPayment  = $request->isterminalPayment;
            $order->staffDiscount = $request->staffDiscount;
            $order->sessionId = $request->sessionId;
            $order->orderCreationWithScript = $request->orderCreationWithScript;
            $order->isOfficeUse  = $request->isOfficeUse;
            $order->orderSource  = $request->orderSource;
            $order->posStaffIdentity = $request->posStaffIdentity;
            $order->dateNightPass  = $request->dateNightPass;
            $order->orderCreationDate = $date;
            $order->transactionId = $request->transactionId;
            $order->saleFormName = $request->saleFormName;
            $order->notes  = $request->notes;
            $order->user_id  = $request->user_id;
            $order->totalAmount = $request->totalAmount;
            $order->save();

            if ($request->purchases) {
                foreach ($request->purchases as $purchase) {
                    $decoded = json_decode($purchase, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        continue; 
                    }
                    $item = new OrderItem;
                    $item->order_id = $order->id;
                    $item->ticketType = $decoded['ticketType'];
                    $item->sectionId = $decoded['sectionId'];
                    $item->capacityId = $decoded['capacityId'];
                    $item->quantity = $decoded['quantity'];
                    $item->amount = $decoded['amount'];
                    $item->save();
                }
            }

            $total_item_quantity = OrderItem::where('order_id',$order->id)->sum('quantity');
            $transaction = new Transaction;
            $transaction->auth_code  = Helper::GeneralSiteSettings('auth_code_en',true);
            $transaction->order_id = $order->id;
            $transaction->transactionId = $order->transactionId;
            $transaction->totalItem = count($request->purchases);
            $transaction->quantity = $total_item_quantity;
            $transaction->transactionDate = $date;
            $transaction->amount = $decoded['amount'];
            $transaction->save();
            $get_order = Order::with(['customer','purchases','transaction'])->where('id',$order->id)->first();
            $resource = OrderResource::make($get_order);
            return $this->sendResponse(200, 'Your Order has been successfully created', $resource);

        } catch (\Exception $e) {
             return $this->sendResponse(400, 'Server Error', $e->getMessage());
        }
    }
}
