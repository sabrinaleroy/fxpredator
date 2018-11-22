<script type="text/javascript">
jQuery(function() {
		jQuery("form").submit(function() {
    jQuery("input").removeAttr("disabled");
});
        var presenceDiv = jQuery('#Presence');
        var ipres = jQuery('#Presence > span').length ;
        jQuery('#addPresence').live('click', function() {
                jQuery('<span><input type="text" id="presence[' + ipres +']" size="20" name="presence[' + ipres +']" value="" placeholder="Vendredi ou 01/01/2017" /></label> <a href="#" id="remPresence">Supprimer</a><br/></span>').appendTo(presenceDiv);
                ipres++;
                return false;
        });
        
        jQuery('#remPresence').live('click', function() { 
                if( ipres > 1 ) {
                        jQuery(this).parents('span').remove();
                        ipres--;
                }
                return false;
        });
        
        
        var hotelDiv = jQuery('#Hotel');
        if(hotelDiv.hasClass("show")){
	        hotelDiv.removeClass("show");
        }else{
	        hotelDiv.hide();
        }
        var ihotel =  jQuery('#Hotel > span').length ;
        jQuery('#IsHotel').change(function(){
	        if(jQuery('#IsHotel').val() == '1') {
	            hotelDiv.show(); 
	        } else {
	            hotelDiv.hide(); 
	        } 
	    });
        
        jQuery('#addHotel').live('click', function() {
                jQuery('<span><input type="text" id="hotel[' + ihotel +']" size="20" name="hotel[' + ihotel +']" value="" placeholder="Vendredi soir ou 01/01/2017 au soir" /></label> <a href="#" id="remHotel">Supprimer</a><br/></span>').appendTo(hotelDiv);
                ihotel++;
                return false;
        });
        
        jQuery('#remHotel').live('click', function() {
                if( ihotel > 1 ) {
                        jQuery(this).parents('span').remove();
                        ihotel--;
                }
                return false;
        });
        
        
        var RestoDiv = jQuery('#Resto');
        if(RestoDiv.hasClass("show")){
	        RestoDiv.removeClass("show");
        }else{
	        RestoDiv.hide();
        }
        
        jQuery('#IsResto').change(function(){
	        if(jQuery('#IsResto').val() == '1') {
	            RestoDiv.show(); 
	        } else {
	            RestoDiv.hide(); 
	        } 
	    });

});
</script>
<style>
	table.participants th{
		text-align: center;
		vertical-align: bottom;
		border-right: 1px solid #aaa;
	}
	
	table.participants th.jour{
		border-bottom: 1px solid #ddd;
	}
	table.participants th.total{
		font-size: 18px;
		font-weight: 900;
	}
	
	table.participants td{
		border-right: 1px solid #eee;
		border-bottom: 1px solid #ddd;
		text-align: center;
	}
	table.participants td:last-child,
	table.participants th.jour:last-child,
	table.participants th.titre:last-child{
		border-right: 0px solid ;
	}
	table.participants td.oui{
		color:#277028;
		font-weight: 900;
	}
	table.participants td.non{
		color:#ccc;
		font-weight: 100;
	}
	a.red{
		color:#f04444;
	}
</style>