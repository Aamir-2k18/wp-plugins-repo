<?php /*Template Name:Zip Download*/?>
<?php 
if( have_rows('memorials_imgae_details',$_GET['pid']) ): while ( have_rows('memorials_imgae_details',$_GET['pid']) ) : the_row();
$free_video_download_file = get_sub_field('image',$_GET['pid']);
$files[]=$free_video_download_file;
//echo '<br>';
endwhile; endif;

//echo $_GET['pid']; 
//print_r($files);



$zip = new ZipArchive();


$tmp_file = tempnam('.', '');
$zip->open($tmp_file, ZipArchive::CREATE);


foreach ($files as $file) {

    $download_file = file_get_contents($file);


    $zip->addFromString(basename($file), $download_file);
}


$zip->close(); 

header('Content-disposition: attachment; filename="souvenirs-images.zip"');
header('Content-type: application/zip');
readfile($tmp_file);
unlink($tmp_file);