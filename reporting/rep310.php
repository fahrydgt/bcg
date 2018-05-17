<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_SALESANALYTIC';
// ----------------------------------------------------------------
// $ Revision:	2.0 $
// Creator:	Chaitanya
// date_:	2005-05-19
// Title:	Sales Summary Report
// ----------------------------------------------------------------
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/data_checks.inc");
include_once($path_to_root . "/includes/banking.inc");
include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");

//----------------------------------------------------------------------------------------------------

print_inventory_sales();

function getTransactions($category, $from, $to)
{
	$from = date2sql($from);
	$to = date2sql($to);
	$sql = "SELECT item.category_id,
			category.description AS cat_description,
			item.stock_id,
			item.description,
			line.unit_price * trans.rate AS unit_price,
			SUM(IF(line.debtor_trans_type = ".ST_CUSTCREDIT.", -line.quantity, line.quantity)) AS quantity
		FROM ".TB_PREF."stock_master item,
			".TB_PREF."stock_category category,
			".TB_PREF."debtor_trans trans,
			".TB_PREF."debtor_trans_details line
		WHERE line.stock_id = item.stock_id
		AND item.category_id=category.category_id
		AND line.debtor_trans_type=trans.type
		AND line.debtor_trans_no=trans.trans_no
		AND trans.tran_date>='$from'
		AND trans.tran_date<='$to'
		AND line.quantity<>0
		AND item.mb_flag <>'F'
		AND (line.debtor_trans_type = ".ST_SALESINVOICE." OR line.debtor_trans_type = ".ST_CUSTCREDIT.")";
		if ($category != 0)
			$sql .= " AND item.category_id = ".db_escape($category);
		$sql .= " GROUP BY item.category_id,
			category.description,
			item.stock_id,
			item.description,
			line.unit_price
		ORDER BY item.category_id, item.stock_id, line.unit_price";
			
	//display_notification($sql);
	
    return db_query($sql,"No transactions were returned");

}

//----------------------------------------------------------------------------------------------------

function print_inventory_sales()
{
    global $path_to_root;
 
    $category = $_POST['PARAM_2'];
	$comments = $_POST['PARAM_3'];
	$orientation = $_POST['PARAM_4'];
	$destination = $_POST['PARAM_5'];
//        echo '<pre>'; print_r($_POST);die;
//        
//        $data_rep = json_encode($_POST);
    global $Ajax;
    $Ajax->popup('http://localhost/bigcity_acc/reporting/print_barcode.php?item_code='.$_POST['PARAM_0'].'&count='.$_POST['PARAM_1']);
//Close and output PDF document
//$pdf_fl->Output('example_002.pdf', 'I');

}

