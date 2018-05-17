<?php
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
$path_to_root="..";

include_once($path_to_root . "/includes/session.inc"); 
//include_once($path_to_root . "/gl/includes/gl_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_db.inc");
include_once($path_to_root . "/inventory/includes/db/items_category_db.inc");
require_once('includes/tcpdf_fl/tcpdf.php');

//$rep_data = json_decode($_GET['data'],true);
//echo '<pre>';print_r($_GET);die;
$item_code = $_GET['item_code']; 
$bc_count = $_GET['count'];
$item_info = get_item($item_code);
$item_cat_info = get_item_category($item_info['category_id']);

//echo '<pre>';print_r($bc_count);die;
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('FAHRY LAFIR');
$pdf->SetTitle('NVELOOP SOLUTION');
$pdf->SetSubject('BARCODE PRINT');
$pdf->SetKeywords('BARCODE');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(0,0,0);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', 'BI', 20);

// add a page
$pdf->AddPage();// EAN 13 

// define barcode style
$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);

 require_once(dirname(__FILE__).'/includes/tcpdf_fl/tcpdf_barcodes_1d.php');

 
$barcodeobj = new TCPDFBarcode($item_info['stock_id'], 'C128');
 
$img =  $barcodeobj->getBarcodePngData(1.5,20); 
//$data = file_get_contents($img);
$base64 = 'data:image/png;base64,' . base64_encode($img);  

$item_seriel = $item_cat_info['parent_id'].$item_cat_info['category_code'].$item_info['stock_id'];
$item_desc = explode("-", $item_info['description'])[0];
$rows = $bc_count/6;

$html = '<table border="0.2" cellpadding="2" > <tbody>';
for($i=1;$i<=$rows;$i++){
 $html .= '<tr>
                       <td style="padding:10px;line-height:16mm; overflow:hidden;"><table border="0" >
                                    <tr>
                                        <td colspan="3"  style="line-height:1mm;padding-top: 2px;"></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="3"  style="line-height:3mm;padding-top: 2px;"><span style="text-align:center; font-size:10px;">SYMI HOLDINGS</span></td> 
                                    </tr>
                                    <tr>
                                        <td width="18%" style="line-height:8mm;"><p style="font-size:8px;">S001</p></td>
                                        <td width="80%" style="line-height:8mm;"><img src="'.$base64.'"></td>
                                        <td width="2%" style="line-height:8mm;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" width="50%" style="line-height:4mm;"><span style="font-size:7.5px;text-align:center">'.$item_seriel.'</span></td>
                                        <td width="50%" style="line-height:4mm;"><span style="font-size:7px;text-align:center">'.$item_desc.'</span></td> 
                                    </tr>
                                </table>
                        </td>  
                       <td style="padding:10px;line-height:16.5mm; overflow:hidden;"><table border="0" >
                                    <tr>
                                        <td colspan="3"  style="line-height:1mm;padding-top: 2px;"></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="3"  style="line-height:3mm;padding-top: 2px;"><span style="text-align:center; font-size:10px;">SYMI HOLDINGS</span></td> 
                                    </tr>
                                    <tr>
                                        <td width="18%" style="line-height:8mm;"><p style="font-size:8px;">S001</p></td>
                                        <td width="80%" style="line-height:8mm;"><img src="'.$base64.'"></td>
                                        <td width="2%" style="line-height:8mm;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" width="50%" style="line-height:4mm;"><span style="font-size:7.5px;text-align:center">'.$item_seriel.'</span></td>
                                        <td width="50%" style="line-height:4mm;"><span style="font-size:7px;text-align:center">'.$item_desc.'</span></td> 
                                    </tr>
                                </table>
                        </td>  
                       <td style="padding:10px;line-height:16.5mm; overflow:hidden;"><table border="0" >
                                    <tr>
                                        <td colspan="3"  style="line-height:1mm;padding-top: 2px;"></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="3"  style="line-height:3mm;padding-top: 2px;"><span style="text-align:center; font-size:10px;">SYMI HOLDINGS</span></td> 
                                    </tr>
                                    <tr>
                                        <td width="18%" style="line-height:8mm;"><p style="font-size:8px;">S001</p></td>
                                        <td width="80%" style="line-height:8mm;"><img src="'.$base64.'"></td>
                                        <td width="2%" style="line-height:8mm;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" width="50%" style="line-height:4mm;"><span style="font-size:7.5px;text-align:center">'.$item_seriel.'</span></td>
                                        <td width="50%" style="line-height:4mm;"><span style="font-size:7px;text-align:center">'.$item_desc.'</span></td> 
                                    </tr>
                                </table>
                        </td>  
                       <td style="padding:10px;line-height:16.5mm; overflow:hidden;"><table border="0" >
                                    <tr>
                                        <td colspan="3"  style="line-height:1mm;padding-top: 2px;"></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="3"  style="line-height:3mm;padding-top: 2px;"><span style="text-align:center; font-size:10px;">SYMI HOLDINGS</span></td> 
                                    </tr>
                                    <tr>
                                        <td width="18%" style="line-height:8mm;"><p style="font-size:8px;">S001</p></td>
                                        <td width="80%" style="line-height:8mm;"><img src="'.$base64.'"></td>
                                        <td width="2%" style="line-height:8mm;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" width="50%" style="line-height:4mm;"><span style="font-size:7.5px;text-align:center">'.$item_seriel.'</span></td>
                                        <td width="50%" style="line-height:4mm;"><span style="font-size:7px;text-align:center">'.$item_desc.'</span></td> 
                                    </tr>
                                </table>
                        </td>  
                       <td style="padding:10px;line-height:16.5mm; overflow:hidden;"><table border="0" >
                                    <tr>
                                        <td colspan="3"  style="line-height:1mm;padding-top: 2px;"></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="3"  style="line-height:3mm;padding-top: 2px;"><span style="text-align:center; font-size:10px;">SYMI HOLDINGS</span></td> 
                                    </tr>
                                    <tr>
                                        <td width="18%" style="line-height:8mm;"><p style="font-size:8px;">S001</p></td>
                                        <td width="80%" style="line-height:8mm;"><img src="'.$base64.'"></td>
                                        <td width="2%" style="line-height:8mm;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" width="50%" style="line-height:4mm;"><span style="font-size:7.5px;text-align:center">'.$item_seriel.'</span></td>
                                        <td width="50%" style="line-height:4mm;"><span style="font-size:7px;text-align:center">'.$item_desc.'</span></td> 
                                    </tr>
                                </table>
                        </td>  
                       <td style="padding:10px;line-height:16.5mm; overflow:hidden;"><table border="0" >
                                    <tr>
                                        <td colspan="3"  style="line-height:1mm;padding-top: 2px;"></td> 
                                    </tr>
                                    <tr>
                                        <td colspan="3"  style="line-height:3mm;padding-top: 2px;"><span style="text-align:center; font-size:10px;">SYMI HOLDINGS</span></td> 
                                    </tr>
                                    <tr>
                                        <td width="18%" style="line-height:8mm;"><p style="font-size:8px;">S001</p></td>
                                        <td width="80%" style="line-height:8mm;"><img src="'.$base64.'"></td>
                                        <td width="2%" style="line-height:8mm;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" width="50%" style="line-height:4mm;"><span style="font-size:7.5px;text-align:center">'.$item_seriel.'</span></td>
                                        <td width="50%" style="line-height:4mm;"><span style="font-size:7px;text-align:center">'.$item_desc.'</span></td> 
                                    </tr>
                                </table>
                        </td>   
                </tr> ';
        }
         $html .= '</tbody></table>'; 
//         echo $html; die;
// print a block of text using Write()
$pdf->writeHTMLCell(210,20,0,0,$html);

// ---------------------------------------------------------

 
 
//Close and output PDF document
$pdf->Output('example_002.png', 'I');

//============================================================+
// END OF FILE
//============================================================+
