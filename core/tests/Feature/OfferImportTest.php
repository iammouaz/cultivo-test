<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Offer;
use App\Models\OfferSheet;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;


class OfferImportTest extends TestCase
{
    public function testImportAccountFromCsv(): void
    {
        Size::whereIn('id', [111, 112, 113, 114, 115, 116])->forceDelete();
        OfferSheet::whereIn('id', [114, 115, 116, 117])->forceDelete();
        DB::insert('insert into offer_sheets (id,status,name,sname,description,image,banner_logo,card_logo,deposit,category_id,created_at,updated_at,offer_sheet_url,emails,show_add_sample_button,show_add_order_button,show_make_offer_button,deleted_at)
values (114,1,\'test\',\'test\',\'test\',\'test\',\'test\',\'test\',0,1,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',\'test\',\'test\',1,1,1,NULL)');
        DB::insert('insert into offer_sheets (id,status,name,sname,description,image,banner_logo,card_logo,deposit,category_id,created_at,updated_at,offer_sheet_url,emails,show_add_sample_button,show_add_order_button,show_make_offer_button,deleted_at)
values (115,1,\'test\',\'test\',\'test\',\'test\',\'test\',\'test\',0,1,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',\'test\',\'test\',1,1,1,NULL)');
        DB::insert('insert into offer_sheets (id,status,name,sname,description,image,banner_logo,card_logo,deposit,category_id,created_at,updated_at,offer_sheet_url,emails,show_add_sample_button,show_add_order_button,show_make_offer_button,deleted_at)
values (116,1,\'test\',\'test\',\'test\',\'test\',\'test\',\'test\',0,1,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',\'test\',\'test\',1,1,1,NULL)');
        DB::insert('insert into offer_sheets (id,status,name,sname,description,image,banner_logo,card_logo,deposit,category_id,created_at,updated_at,offer_sheet_url,emails,show_add_sample_button,show_add_order_button,show_make_offer_button,deleted_at)
values (117,1,\'test\',\'test\',\'test\',\'test\',\'test\',\'test\',0,1,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',\'test\',\'test\',1,1,1,NULL)');
        DB::insert('insert into sizes (id,size,weight_LB,additional_cost,offer_sheet_id,is_sample,created_at,updated_at,deleted_at)
values (111,\'sack 35kg\',23,0,114,0,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',NULL)');
        DB::insert('insert into sizes (id,size,weight_LB,additional_cost,offer_sheet_id,is_sample,created_at,updated_at,deleted_at)
values (112,\'sack\',20,0,114,0,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',NULL)');
        DB::insert('insert into sizes (id,size,weight_LB,additional_cost,offer_sheet_id,is_sample,created_at,updated_at,deleted_at)
values (113,\'sack 35kg\',23,0,115,0,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',NULL)');
        DB::insert('insert into sizes (id,size,weight_LB,additional_cost,offer_sheet_id,is_sample,created_at,updated_at,deleted_at)
values (114,\'sack 72kg\',43,0,115,0,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',NULL)');
        DB::insert('insert into sizes (id,size,weight_LB,additional_cost,offer_sheet_id,is_sample,created_at,updated_at,deleted_at)
values (115,\'sack 35kg\',23,0,116,0,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',NULL)');
        DB::insert('insert into sizes (id,size,weight_LB,additional_cost,offer_sheet_id,is_sample,created_at,updated_at,deleted_at)
values (116,\'sack 35kg\',23,0,117,0,\'2021-09-01 00:00:00\',\'2021-09-01 00:00:00\',NULL)');


        $this->offerService = app('offerService');
        $pathCsv = base_path('tests/Feature/Files/offers.csv');
        $this->offerService->importOfferFromCsv($pathCsv,'test',basename($pathCsv));

        $offers = Offer::query()->where('offer_sheet_id',14)->get();
        $this->assertEquals(13, $offers->count());


    }
}
