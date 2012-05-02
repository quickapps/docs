This will render out all blocks assigned to the region 'my-theme-region' of your theme

    <?php echo $this->Layout->blocks('my-theme-region'); ?>
    

Now, for example you would like to show certain area/region of your theme only if there are blocks availables to show on it.
This allows you for example hide the left column of your layout if there are no blocks to show on it and use all the available width for the rest of your content.
     
    <?php if (!$this->Layout->emptyRegion('my-theme-region')): ?>
        <div class="left-column">
            <?php echo $this->Layout->blocks('my-theme-region'); ?>
        </div>
    <?php endif; ?>

