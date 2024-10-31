<?php

/**
 * Created by PhpStorm.
 * User: phucn
 * Date: 9/27/2016
 * Time: 4:09 PM
 */

namespace RMS\Views;
use RMS\Bizs\InfusionSoftTagBiz;

class ProductMetaBoxView
{
   static function content($post){
       $infusion_Biz = new InfusionSoftTagBiz();
       $rms_commission = get_post_meta( $post->ID, '_rms_commission', true );
       $rms_commission = $rms_commission?$rms_commission:0;
       $setting = null;
       function get_data($data,$field){
           return isset($data)?$data->{$field}:'';
       }

       $infusion = get_post_meta( $post->ID, '_infusion_tags', true );
       $tags = $infusion_Biz->get_infusion();
       $tags_selected = explode(',',$infusion);
?>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css" type="text/css">
       <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
       <table class="form-table stm product-meta-box">
           <tr>
               <th><label>Hoa hồng Affiliate (%):</label></th>
               <td><input type="number" min="0" id="rms_commission" name="rms_commission" value="<?php echo esc_attr( $rms_commission ) ?>" > <span>%</span></td>
           </tr>
           <tr>
               <th><label>Infusion Tags:</label></th>
               <td>
                <?php 
                  
                ?>
                <input type="text" id="infusion_name" name="infusion_tags">
                
               </td>
           </tr>

       </table>

       <script language="JavaScript">
        jQuery( function(){
          jQuery('#infusion_name').selectize({
            delimiter: ',',
            persist: false,
            placeholder: 'Chọn tags',
            options: [
              <?php
              foreach ($tags as $index => $tag){
               if($tag->infusion_name != '' && ($tag->infusion_name != '0')){
                  echo "{value: '$tag->infusion_name', text:'$tag->infusion_name'}";
                  echo ($index+1)<count($tags)?',':'';
               }
              }
              ?>
            ],
            items: [
              <?php
              foreach ($tags as $index => $tag){
               if($tag->infusion_name != '' && ($tag->infusion_name != '0') && in_array($tag->infusion_name,$tags_selected)){
                  echo "'$tag->infusion_name'";
                  echo ($index+1)<count($tags)?',':'';
               }
              }
              ?>
            ],
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
          });
        });
       </script>
        <?php
   }
}