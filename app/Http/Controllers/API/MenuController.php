<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Auth;

class MenuController extends Controller
{
    public function index(Request $request){
        try {
            $Query = Menu::with(["parent","childs","status"]);

            if($request->has('parent') && is_numeric($request->parent)){
                $Query->where("parent",$request->parent);
            }

            if($request->has('key')){
                $Query->where(function($query) use($request) {
                                $query->orWhereRaw("lower(name) like '%".strtolower($request->key)."%'");
                            });
            }

            if($request->has('orderby') && $request->has('sort')){
                $Query->orderby($request->orderby,$request->sort);
            }

            $total = $Query->count();

            if($request->has('limit') && $request->has('offset')){
                $Query->skip($request->offset)
                        ->take($request->limit);
            }

            if($request->has('id') && is_numeric($request->id)){
                $Query = $Query->where("id",$request->id);
                $total = $Query->count();
                $Query = $Query->first();
            }else{
                $Query = $Query->get();
            }

            $return = [
                "rows" => $Query,
                "total" => $total,
            ];

            return successResp("",$return);

        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function store(Request $request){
        try {
            $data               = new Menu;
            $data->name         = $request->name;
            $data->parent       = $request->parent;
            $data->link         = $request->link;
            $data->icon         = $request->icon;
            $data->sequence     = $request->sequence;
            $data->status       = $request->status;
            $data->save();
            return successResp("Successfully saved data");
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function update(Request $request){
        try {

            if($request->has('id') && is_numeric($request->id)){
                $data               = Menu::find($request->id);
                if( $data ){
                    $data->name         = $request->name;
                    $data->parent       = $request->parent;
                    $data->link         = $request->link;
                    $data->icon         = $request->icon;
                    $data->sequence     = $request->sequence;
                    $data->save();
                    return successResp("Successfully saved data");
                }else{
                    return errorResp("Data cannot be found!",422);
                }
            }else{
                return errorResp("Data cannot be found!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }

    public function delete(Request $request){
        try {
            if($request->has('id') && is_numeric($request->id)){
                $data               = Menu::find($request->id);
                if( $data ){
                    $data->delete();
                    return successResp("Successfully delete data");
                }else{
                    return errorResp("Data cannot be found!",422);
                }
            }else{
                return errorResp("Data cannot be found!",422);
            }
        } catch (Exception $e) {
            return errorResp($e->getMessage(),422);
        }
    }
}
