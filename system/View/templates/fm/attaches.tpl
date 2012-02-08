<script type="text/javascript">
$("#arfiles").droppable({
    tolerance: "touch",
    accept: ".fm_pre",
    drop: function(event, ui) {
        $(ui.helper).hide();
        
        var dir = getCurDirName();
        var fname = ui.draggable.text();

        $("#attach_files").append("<input type='hidden' name='attaches[]' value='{{ registry.path.root }}/{{ registry.path.upload }}" + dir + fname + "' /><p><img border='0' src='{{ registry.uri }}img/paper-clip-small.png' alt='attach' style='position: relative; top: 4px; left: 1px' />" + fname + "</p>");
    }
});
</script>