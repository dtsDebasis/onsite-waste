<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionalPackage extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'transactional_packages';

    protected $fillable = [
        'id',
        'te_5000_rental_cost',
        'te_5000_purchase_cost',
        'containers_cost',
        'shipping_cost',
        'setup_initial_cost',
        'setup_additional_cost',
        'compliance_training_cost',
        'quarterly_review_cost',
        'additional_trip_cost',
        'additional_box_cost',
        'container_brackets_cost',
        'pharmaceutical_containers_rate',
        'company_id',
        'branch_id',
        'is_active',
        'created_at'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    public $orderBy = [];

    public function getListing($srch_params = [], $offset = ''){
        $with_det = [];
        if(isset($srch_params['with'])){
            $with_det = $srch_params['with'];
        } 
        $select = '*';
        if(isset($srch_params['select'])){
            $select = $srch_params['select'];//implode(',',$srch_params['select']);
        }
        $listing = self::select($select)->with($with_det)->where($this->table .'.deleted_at',NULL)
            ->when(isset($srch_params['company_id']), function($q) use($srch_params){
                return $q->where($this->table.".company_id", "=", $srch_params['company_id']);
            })
            ->when(isset($srch_params['branch_id']), function($q) use($srch_params){
                if(is_array($srch_params['branch_id'])){
                    return $q->whereIn($this->table.".branch_id", $srch_params['branch_id']);
                }
                else{
                    return $q->where($this->table.".branch_id",'=', $srch_params['branch_id']);
                }
            });
            if(isset($srch_params['id'])){
                return $listing->where($this->table .'.id', '=', $srch_params['id'])
                                ->first();
            }
            if(isset($srch_params['single_record'])){
                return $listing->latest()->first();
            }
            if(isset($srch_params['count'])){
                return $listing->count();
            }                        
            if($offset){
                $listing = $listing->orderBy($this->table .'.id', 'Asc')
                                ->paginate($offset);
            }
            else{
                $listing = $listing->orderBy($this->table .'.id', 'ASC')
                                ->get();
            }
        return $listing;
            
    }

}
