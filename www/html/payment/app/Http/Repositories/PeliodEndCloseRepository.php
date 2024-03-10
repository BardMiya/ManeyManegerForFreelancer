<?php
namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;

class PeliodEndCloseRepository{
    /**
     * Calculation of profit and loss statement
     *
     * @param  int  $year
     * @param  string  $servicer
     * @return Object
     */
    public function statement($year, $servicer)
    {
        // ts_transaction
        $q = <<<SQL
            SELECT T.transactioner, 
                   T.Item AS 'item',
                   T.Item_cd AS 'item_cd',
                   T.type, 
                   T.account_cd, 
                   T.name, 
                   ABS(SUM((CASE D.type WHEN 2 THEN 1 WHEN 1 THEN -1 ELSE 0 END) * IFNULL(price,0))) AS SUMMARY
            FROM  (
               SELECT DISTINCT
                    PRN.transaction_no, 
                    PRN.transactioner,
                    PRN.name AS 'Item',
                    PRN.account_cd AS 'Item_cd',
                    PRN.transaction_date, 
                    PA.account_cd, 
                    PA.name,
                    PA.type
                FROM (
                    SELECT ST.transaction_no, ST.transactioner, IA.name, IA.account_cd, ST.transaction_date 
                    FROM 
                        ts_transaction ST
                    INNER JOIN 
                        ts_transaction_detail I
                    ON 
                        ST.transaction_no = I.transaction_no
                        AND I.valid_flg IS NOT NULL
                    INNER JOIN 
                        mt_account IA
                    ON 
                        I.account_CD = IA.account_cd
                        AND IA.valid_flg IS NOT NULL
                    WHERE
                        IA.type IN (3,4,5) AND IA.account_cd NOT IN ('KBZ', 'KUZ', 'MIK')
                        AND ST.valid_flg IS NOT NULL
                        AND (:tran1 IS NULL OR ST.transactioner = :tran2)
                        AND YEAR(ST.transaction_date) = :year1
               ) PRN
                INNER JOIN
                    (
                        cb_transactioner_account TA
                        INNER JOIN
                        mt_account PA
                        ON TA.account_cd = PA.account_cd
                        AND PA.valid_flg IS NOT NULL
                        AND PA.type < 3
                    )
                ON
                    PRN.transactioner = TA.transactioner
             ) T
            LEFT JOIN
            ts_transaction_detail D
            ON T.transaction_no = D.transaction_no
            AND T.account_cd = D.account_CD
            AND D.valid_flg IS NOT NULL
            WHERE (:tran3 IS NULL OR T.transactioner = :tran4)
                AND YEAR(T.transaction_date) = :year2
            GROUP BY T.transactioner, T.Item, T.Item_cd, T.type, T.account_cd, T.name
            UNION ALL
            SELECT T.transactioner, 
                   '合計' AS 'item',
                   '000' AS 'item_cd',
                   T.type, 
                   T.account_cd, 
                   T.name, 
                   ABS(SUM((CASE D.type WHEN 2 THEN 1 WHEN 1 THEN -1 ELSE 0 END) * IFNULL(price,0))) AS SUMMARY
            FROM  (
               SELECT DISTINCT
                    PRN.transaction_no, 
                    PRN.transactioner,
                    PRN.transaction_date, 
                    PA.account_cd, 
                    PA.name,
                    PA.type
                FROM (
                    SELECT DISTINCT ST.transaction_no, ST.transactioner, ST.transaction_date 
                    FROM 
                        ts_transaction ST
                    INNER JOIN 
                        ts_transaction_detail I
                    ON 
                        ST.transaction_no = I.transaction_no
                        AND I.valid_flg IS NOT NULL
                    WHERE
                        ST.valid_flg IS NOT NULL
                        AND (:tran5 IS NULL OR ST.transactioner = :tran6)
                        AND YEAR(ST.transaction_date) = :year3
                ) PRN
                INNER JOIN
                    (
                        cb_transactioner_account TA
                        INNER JOIN
                        mt_account PA
                        ON TA.account_cd = PA.account_cd
                        AND PA.valid_flg IS NOT NULL
                        AND PA.type < 3
                    )
                ON
                    PRN.transactioner = TA.transactioner
             ) T
            LEFT JOIN
            ts_transaction_detail D
            ON T.transaction_no = D.transaction_no
            AND T.account_cd = D.account_CD
            AND D.valid_flg IS NOT NULL
            WHERE (:tran7 IS NULL OR T.transactioner = :tran8)
                AND YEAR(T.transaction_date) = :year4
            GROUP BY T.transactioner, T.type, T.account_cd, T.name
SQL;
        $param = [
            'year1' => $year,
            'year2' => $year,
            'year3' => $year,
            'year4' => $year,
            'tran1' => $servicer,
            'tran2' => $servicer,
            'tran3' => $servicer,
            'tran4' => $servicer,
            'tran5' => $servicer,
            'tran6' => $servicer,
            'tran7' => $servicer,
            'tran8' => $servicer,
        ];
        $result = DB::select($q, $param);
        return $result;
    }
    /**
     * Calculation of Balance sheeet
     *
     * @param  int  $year
     * @param  string  $servicer
     * @return Object
     */
    public function balance($year, $servicer = null)
    {
        // ts_transaction
        $q = <<<SQL
            SELECT * FROM (
                SELECT 
                    transactioner, 
                    ACT.type, 
                    ACT.name AS 'category', 
                    D.account_CD AS 'account_cd',
                    A.name, 
                    SUM((CASE D.type WHEN 2 THEN 1 WHEN 1 THEN -1 ELSE 0 END) * price) AS SUMMARY
                FROM 
                    ts_transaction_detail D
                INNER JOIN 
                    mt_account A
                ON 
                    D.account_CD = A.account_cd
                AND A.valid_flg IS NOT NULL
                INNER JOIN 
                    ts_transaction T
                ON 
                    D.transaction_no = T.transaction_no
                AND ( T.remark <> 'CLOSE' OR T.remark IS NULL) 
                AND T.valid_flg IS NOT NULL
                INNER JOIN (
                        SELECT 1 AS type, '収益' AS name
                        UNION ALL
                        SELECT 2 AS type, '費用' AS name
                        UNION ALL
                        SELECT 3 AS type, '資産' AS name
                        UNION ALL
                        SELECT 4 AS type, '負債' AS name
                        UNION ALL
                        SELECT 5 AS type, '資本' AS name
                        UNION ALL
                        SELECT 6 AS type, '損益' AS name
                ) ACT
                ON 
                    A.type = ACT.type
                WHERE 
                    D.valid_flg IS NOT NULL
                    AND (:tran1 IS NULL OR T.transactioner = :tran2)
                    AND YEAR(T.transaction_date) = :year1
                GROUP BY 
                    T.transactioner, 
                    ACT.type, 
                    ACT.name, 
                    D.account_CD, 
                    A.name
                UNION ALL
                SELECT 
                    transactioner, 
                    ACT.type, 
                    ACT.name AS 'category', 
                    '000' AS 'account_cd', 
                    '合計' AS 'name', 
                    SUM((CASE D.type WHEN 2 THEN 1 WHEN 1 THEN -1 ELSE 0 END) * price) AS SUMMARY
                FROM 
                    ts_transaction_detail D
                INNER JOIN 
                    mt_account A
                ON 
                    D.account_CD = A.account_cd
                    AND A.valid_flg IS NOT NULL
                INNER JOIN 
                    ts_transaction T
                ON 
                    D.transaction_no = T.transaction_no
                    AND ( T.remark <> 'CLOSE' OR T.remark IS NULL) 
                    AND T.valid_flg IS NOT NULL
                INNER JOIN (
                        SELECT 1 AS TYPE, '収益' AS name
                        UNION ALL
                        SELECT 2 AS TYPE, '費用' AS name
                        UNION ALL
                        SELECT 3 AS TYPE, '資産' AS name
                        UNION ALL
                        SELECT 4 AS TYPE, '負債' AS name
                        UNION ALL
                        SELECT 5 AS TYPE, '資本' AS name
                        UNION ALL
                        SELECT 6 AS type, '損益' AS name
                ) ACT
                ON 
                    A.type = ACT.type
                WHERE 
                    D.valid_flg IS NOT NULL
                    AND (:tran3 IS NULL OR T.transactioner = :tran4)
                    AND YEAR(T.transaction_date) = :year2
                GROUP BY 
                    T.transactioner, 
                    ACT.type, 
                    ACT.name
            ) B
            ORDER BY 
                transactioner, 
                type, 
                account_CD
SQL;
        $param = [
            'year1' => $year,
            'year2' => $year,
            'tran1' => $servicer,
            'tran2' => $servicer,
            'tran3' => $servicer,
            'tran4' => $servicer,
        ];
        $result = DB::select($q, $param);
        return $result;
    }
}
