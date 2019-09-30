nz_co_fuzion_cybersource
========================

CiviCRM CyberSource Payment processor extension

 @Source code and original idea taken from Jason Yee. Modified, upgraded and fixed by Victor Forsythe 
 * (support@upleaf.com) for newer versions of civicrm.
 * www.upleaf.com. Further fixes and 4.2 packaging by Eileen McNaughton commissioned by upleaf.com.
 In order to use this you will have to generate a signature file (HOP.php) within cybersource. You can discard the file
 but within if you will find the Merchant ID, Shared Secret and Serial Number to configure the processor
 Note that the signature authentication provided for by Cybersource has not been implemented. 
 Cybersource dev docs are : http://apps.cybersource.com/library/documentation/sbc/SOP_UG/html/wwhelp/wwhimpl/js/html/wwhelp.htm#href=app%20C%20-%20samples.html#1014521