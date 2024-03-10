<?php

namespace App\Http\Services;

class PeliodEndCloseService
{
    public function periodClose($year, $servicer)
    {

    }
    /**
     * 損益計算書用データの整形
     * @param  instance  $calcResult  （DBから取得したデータ：損益）
     * @param  instance  $balance  （DBから取得したデータ：貸借対象表データ）
     * @return array
     */
    public function statement($calcResult, $balance)
    {
        $debuger = [];
        $resItems = [];
        $transactioners = array_unique(
            array_map(function($r){ return $r->transactioner;}, $calcResult)
        );
        $types = array_unique(array_map(function($r){
            return $r->item;
        }, $calcResult));
        $itemList =[];
        foreach($transactioners as $transactioner)
        {
            $items = [];
            foreach($types as $item)
            {
                $targets = array_values(array_filter($calcResult, function($rec) use($transactioner, $item)
                        {
                            return $rec->transactioner == $transactioner && $rec->item == $item;
                        }
                ));
                $details = array_map(function($target){
                        return [
                            'type' => $target->type,
                            'account_cd' => $target->account_cd,
                            'name' => $target->name,
                            'SUMMARY' => $target->SUMMARY
                        ];
                    }, $targets);
                array_push($items, [
                    'item' => $item,
                    'item_cd' => $targets[0]->item_cd,
                    'details' => $details
                ]);

                array_push( $debuger, $targets);
            }
            $revenues = array_values(
                array_filter($balance, function($r) use($transactioner)
                {
                    return $r->transactioner == $transactioner && $r->type == 1 && $r->account_cd == '000';
                })
            );
            $revItem = array_shift($revenues);
            $revenue = $revItem == null ? 0 : $revItem->SUMMARY;
            $costs = array_values(
                array_filter($balance, function($r) use($transactioner)
                {
                    return $r->transactioner == $transactioner && $r->type == 2 && $r->account_cd == '000';
                })
            );
            $costItem = array_shift($costs);
            $cost = $costItem == null ? 0 : $costItem->SUMMARY;
            array_push( $itemList, [
                'transactioner' => $transactioner,
                'items' => $items,
                'revenue' => $revenue,
                'cost' => $cost,
                'diff' => $revenue + $cost
            ]);
        }
        return $itemList;
    }
    /**
     * 貸借対照表用データの整形
     * @param  instance  $calcResult  （DBから取得したデータ）
     * @return array
     */
    public function balance($calcResult)
    {
        $resItems = [];
        $transactioner = array_map(function($t) use($calcResult){
            $summary = array_values(
                array_filter($calcResult, function($r)use($t){
                    return $r->account_cd == '000' && $r->transactioner == $t;
                })
            );
            return [
                'transactioner' => $t,
                'barances' => array_map(function($c) use($t, $calcResult){
                    return [
                        'type' => $c->type,
                        'category' => $c->category,
                        'summary' => $c->SUMMARY,
                        'details' => array_map(
                            function($d){
                                return [
                                    'account_cd' => $d->account_cd,
                                    'name' => $d->name,
                                    'summary' => $d->SUMMARY
                                ];
                            },
                            array_values(
                                array_filter($calcResult, function($account) use($t, $c){
                                    return $account->transactioner == $t 
                                        && $account->type == $c->type
                                        && $account->account_cd !== '000';
                                })
                            )
                        )
                    ];
                }, $summary)
                
            ];
        }, array_unique( array_map( function($t){ return $t->transactioner;}, $calcResult)));
        return array_values($transactioner);
    }
}