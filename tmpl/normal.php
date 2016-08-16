<?php
/**
 * @package		Mb2 A-Z Content
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2016 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('_JEXEC') or die;
$i = 0;
?>
<ul class="mb2az-alphabet">
    <?php foreach ($alphabet as $letter) : ?>
		<li>
			<?php if (in_array($letter, $firstTitleChar)) : ?>                
         		<a class="mb2az-scroll" href="#mb2az<?php echo $module->id . $letter; ?>" data-scrollto="mb2az<?php echo $module->id . $letter; ?>"><?php echo $letter; ?></a>
       		<?php else : ?>            	
      			<span><?php echo $letter; ?> </span>               
    		<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul><!-- //end .mb2az-alphabet -->
<ul class="mb2az-articles">
	<?php foreach ($alphabet as $letter) : ?>
 		<?php if (in_array($letter, $firstTitleChar)) : ?> 
     		<li id="mb2az<?php echo $module->id . $letter; ?>" class="mb2az-letter-item">
            	<a class="mb2az-scroll mb2az-back" href="#mb2az-<?php echo $module->id; ?>" data-scrollto="mb2az-<?php echo $module->id; ?>"><?php echo JText::_('MOD_MB2AZCONTENT_BACK_TOTOP'); ?></a>
          		<h4 class="mb2az-letter-item-heading"><?php echo $letter; ?></h4>
         		<ul class="mb2az-letter-item-articles">
           			<?php foreach ($list as $item) : 
						
						// Check first article item
						$x = $list[0]->title == $item->title ? 1 : 0;						
						?>
						<?php if (mb_substr($item->title,$x,1) === $letter) : ?>
                     		<li><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></li>
                  		<?php endif; ?>                        
					<?php endforeach; ?>
          		</ul><!-- //end .mb2az-letter-item-articles -->
         	</li><!-- //end .mb2az-letter-item -->		
		<?php endif; ?>
 	<?php endforeach; ?>
</ul><!-- //end .mb2az-articles -->