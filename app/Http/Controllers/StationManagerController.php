<?php
    
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Station;
use App\Models\Province;
use App\Models\District;
use App\Models\Sector;
use App\Models\Cell;
use App\Models\Vilage;
use App\Models\Station_Data;
use App\Models\sensor_Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StationManagerController extends Controller
{ 

    function __construct()
    {
         $this->middleware('permission:station-list|station-create|station-edit|station-delete', ['only' => ['index','show']]);
         $this->middleware('permission:station-create', ['only' => ['create','store']]);
         $this->middleware('permission:station-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:station-delete', ['only' => ['destroy']]);
    }
  
    public function index()
    {
      
        
    $stations = Station::latest()->paginate(5);
    $provinces= Province::all();
    $districts = District::all();
    $sectors = Sector::all();
    $cells = Cell::all();
    $villages = Vilage::all();
    $managers = User::all();
   
    // return view('Stations.index', compact('stations','provinces', 'districts', 'sectors', 'cells', 'villages', 'managers'))
    // ->with('i', (request()->input('page', 1) - 1) * 5);;

    return response()->json(['stations' => $stations], 200);
    }
  
    public function create(Request $request)
    {
      
        $managers = User::all();
        $province = province::all();
        // $district = District::all();
        $districts = DB::table('districts')
        ->where('province_id', $request->province_id)
        ->get();
    
    if (count($districts) > 0) {
        return response()->json($districts);
    }
        
        // $sector = Sector::all();
        $sectors = DB::table('sectors')
            ->where('district_id', $request->district_id)
            ->get();
        
        if (count($sectors) > 0) {
            return response()->json($sectors);
        }
        // $cell = Cell::all();
        $cells = DB::table('cells')
        ->where('sector_id', $request->sector_id)
        ->get();
    
    if (count($cells) > 0) {
        return response()->json($cells);
    }
        // $village = Vilage::all();
        $villages = DB::table('vilages')
        ->where('cell_id', $request->cell_id)
        ->get();
    
    if (count($villages) > 0) {
        return response()->json($villages);
    }
        return view('stations.create', compact('managers','province','districts','sectors','cells','villages'));
    }
    
    public function getDistricts(Request $request)
    {
        $districts = DB::table('districts')
            ->where('province_id', $request->province_id)
            ->get();
        
        if (count($districts) > 0) {
            return response()->json($districts);
        }
    }
    public function getSectors(Request $request)
    {
        $sectors = DB::table('sectors')
            ->where('district_id', $request->district_id)
            ->get();
        
        if (count($sectors) > 0) {
            return response()->json($sectors);
        }
    }

    public function getCells(Request $request)
    {
        $cells = DB::table('cells')
            ->where('sector_id', $request->sector_id)
            ->get();
        
        if (count($cells) > 0) {
            return response()->json($cells);
        }
    }
    public function getVillages(Request $request)
    {
       $villages = DB::table('villages')
            ->where('cell_id', $request->cell_id)
            ->get();
        
        if (count($villages) > 0) {
            return response()->json($villages);
        }
    }
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'province_id'=>'required',
            'district_id'=>'required',
            'sector_id'=>'required',
            'cell_id'=>'required',
            'village_id'=>'required',
            'details' => 'required',
            'user_id' => 'required',
        ]);
       
        Station::create($request->all());
    
        return redirect()->route('stations.index')
                        ->with('success','station created successfully.');
    }
    
 
    
    public function show($id)
    {
   
        $station = Station::find($id);
        $province =$station->province;
        $district = $station->district;
        $sector = $station->sector;
        $cell = $station->cell;
        $village = $station->vilage;
        $manager = $station->user;
      
        return view('Stations.show', compact('station', 'manager','province', 'district', 'sector', 'cell', 'village'));
    }
   
    public function edit($id)
    {

        $station = Station::find($id);
        $provinces= Province::all();
        $districts = District::all();
        $sectors = Sector::all();
        $cells = Cell::all();
        $villages = Vilage::all();
        $managers = User::all();
     
        return view('stations.edit', compact('station','provinces', 'districts', 'sectors', 'cells', 'villages', 'managers'));
    }
  
    public function update(Request $request, Station $station)
    {
         request()->validate([
            'name' => 'required',
            'details' =>'required',
            'province_id',
            'district_id',
            'sector_id',
            'cell_id',
            'village_id',
            'user_id',
            
        ]);
    
        $station->update($request->all());
    
        return redirect()->route('stations.index')
                        ->with('success','station updated successfully');
    }
    
   
    public function destroy(Station $station)
    {
        $station->delete();
    
        return redirect()->route('stations.index')
                        ->with('success','station deleted successfully');
    }
     public function linechart()
    {
      
        $id = Auth::user()->station->id;
        $data = Station_Data::where('station_id', $id)->latest('created_at')->first();
        
        // Initialize arrays for holding data
        $temperatureData = [];
        $waterLevelData = [];
        $soilMoistureData = [];
        $humidityData = [];
        $timeData = [];

        // Fetch station data
        $stationData = Station_Data::where('station_id', $id)->get();

        // Loop through station data to extract values and time
        foreach ($stationData as $dataPoint) {
            $temperatureData[] = $dataPoint->temperature;
            $waterLevelData[] = $dataPoint->water_level;
            $soilMoistureData[] = $dataPoint->soil_moisture;
            $humidityData[] = $dataPoint->humidity;
            $timeData[] = $dataPoint->created_at->format('Y-m-d H:i:s');
        }

        $temperatureThreshold = 25; // Example threshold for temperature
        $humidityThreshold = 60; // Example threshold for humidity
        $waterLevelThreshold = 50; // Example threshold for water level
        $soilMoistureThreshold = 40; // Example threshold for soil moisture

        // Return the data to the view
        return view('charts.line_chart', compact('temperatureData', 'waterLevelData', 'soilMoistureData', 'humidityData', 'timeData','waterLevelThreshold'));
    }
    
}
