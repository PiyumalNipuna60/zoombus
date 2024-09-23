<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\LaravelLocalization;

class TicketsController extends Controller
{

    public function listTickets(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data = [];
        $salesQ = Sales::current($request->user()->id)->status([1,3]);
        if($salesQ->exists()) {
            $sales = $salesQ->with(['routes','routes.citiesFrom.translated','routes.citiesTo.translated'])->skip($request->skip)->take(10)->orderBy('created_at', 'DESC')->get()->toArray();
            $data['total'] = Sales::current($request->user()->id)->statusNot(2)->count();
            foreach ($sales as $key => $sale) {
                $data['items'][$key] = $sale;
                $data['items'][$key]['departure_date'] = Carbon::parse($sale['routes']['departure_date'])->translatedFormat('j M Y');
                $data['items'][$key]['from'] = $sale['routes']['cities_from']['translated']['name'];
                $data['items'][$key]['to'] = $sale['routes']['cities_to']['translated']['name'];
            }
        }


        return response()->json($data);
    }

    public function single(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $q = Sales::current($request->user()->id)->with(
            'routes',
            'routes.vehicles',
            'routes.vehicles.manufacturers:id,name as manufacturer_name',
            'routes.citiesFrom:id,code as city_code,extension',
            'routes.citiesFrom.translated',
            'routes.citiesTo',
            'routes.citiesTo.translated',
            'routes.addressFrom',
            'routes.addressFrom.translated',
            'routes.addressTo',
            'routes.currency',
            'routes.addressTo.translated',
            'users:id,name',
            'currency:id,key as currency_key'
        )->status([1,3])->where('ticket_number', $request->id)->first();
        if($q) {
            $query = $q->toArray();
            $response = [
                'id' => $q['id'],
                'status' => 1,
                'sale_status' => $q['status'],
                'image' => Storage::temporaryUrl('tickets/'.md5($query['ticket_number']).'.png', now()->addMinutes(5)),
                'ticket_number' => $query['ticket_number'],
                'route_number' => $query['routes']['cities_from']['city_code'].$query['routes']['id'],
                'passenger' => $query['users']['name'],
                'price' => $query['price'],
                'price_currency' => $query['routes']['currency']['key'],
                'from' => $query['routes']['cities_from']['translated']['name'],
                'to' => $query['routes']['cities_to']['translated']['name'],
                'seat_number' => $query['seat_number'],
                'departure_date' => Carbon::parse($query['routes']['departure_date'])->locale($request->lang)->translatedFormat('j\ M Y'),
                'departure_time' => $query['routes']['departure_time'],
                'arrival_time' => $query['routes']['arrival_time'],
                'countdownTimestamp' => Carbon::parse($query['routes']['departure_date'] . ' ' . $query['routes']['departure_time'])->getTimestamp() - Carbon::now()->getTimestamp()
            ];

            return response()->json($response, 200);
        }
        else {
            return response()->json([], 422);
        }
    }

    public function singleSecure(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $q = Sales::current($request->user()->id)->with(
            'routes',
            'routes.vehicles',
            'routes.vehicles.manufacturers:id,name as manufacturer_name',
            'routes.citiesFrom:id,code as city_code,extension',
            'routes.citiesFrom.translated',
            'routes.citiesTo',
            'routes.citiesTo.translated',
            'routes.addressFrom',
            'routes.addressFrom.translated',
            'routes.addressTo',
            'routes.currency',
            'routes.addressTo.translated',
            'users:id,name',
            'currency:id,key as currency_key'
        )->status([1,3])->whereRaw('md5(ticket_number) = ?', $request->id)->first();
        if($q) {
            $query = $q->toArray();
            $response = [
                'id' => $q['id'],
                'status' => 1,
                'sale_status' => $q['status'],
                'image' => Storage::temporaryUrl('tickets/'.md5($query['ticket_number']).'.png', now()->addMinutes(5)),
                'ticket_number' => $query['ticket_number'],
                'passenger' => $query['users']['name'],
                'route_number' => $query['routes']['cities_from']['city_code'].$query['routes']['id'],
                'price' => $query['price'],
                'price_currency' => $query['routes']['currency']['key'],
                'from' => $query['routes']['cities_from']['translated']['name'],
                'to' => $query['routes']['cities_to']['translated']['name'],
                'seat_number' => $query['seat_number'],
                'departure_date' => Carbon::parse($query['routes']['departure_date'])->locale($request->lang)->translatedFormat('j\ M Y'),
                'departure_time' => $query['routes']['departure_time'],
                'arrival_time' => $query['routes']['arrival_time'],
                'countdownTimestamp' => Carbon::parse($query['routes']['departure_date'] . ' ' . $query['routes']['departure_time'])->getTimestamp() - Carbon::now()->getTimestamp()
            ];

            return response()->json($response, 200);
        }
        else {
            return response()->json([], 422);
        }
    }

}
