(function ($) {
    $(function() {
        
        var resizeTimer;
        
        function setCPpadding () {
            ADDGAP = 60;
            
            var cpWidth = $('.container-padding').innerWidth(),
                sbWidth = $('.front #sideblocks').innerWidth(),
                wWidth = $('body').width(),
                sblockWidth = $('#sideblocks').width(),
                fqWidth = $('.front #frontquoute').width(),
                rightGap = (wWidth - cpWidth)/2,
                leftGap =(wWidth - sbWidth)/2,
                overlap = (sblockWidth - rightGap),
                frontOverlap = (fqWidth - leftGap);
                console.log($('#bpMArker:visible').length == 0);
                
            if (overlap > 0 && $('#bpMArker:visible').length == 0) {
                newGap = overlap + ADDGAP;
            } else newGap = 0;
            
            if (frontOverlap > 0 && $('#bpMArker:visible').length == 0) {
                newFrontGap = frontOverlap + ADDGAP;
            } else newFrontGap = 0;
            
            $('.container-padding').css('padding-right', newGap + 'px');
            $('.front #sideblocks').css('padding-left', newFrontGap + 'px');
        }
        
        
        function setCoverHeight () {
            var wHeight = $(window).height();
            
            $('.front .node-news-intro').css('min-height', wHeight + 'px'); 
            $('.front .node-cover').css('height', $('.front .node-news-intro').height() + 'px'); 
            
            var nodeNewsIntroHeight = $('.node-news-intro').height();
            
            $('.page-node.node-type-newspage .node-news-intro .node-cover').css('height', nodeNewsIntroHeight + 'px');
        }
   
        
        $('.webform-client-form input[type="radio"]').change(function () {
            var name = $(this).val();
            var check = $(this).prop('checked');
            //console.log("Change: " + name + " to " + check);
            
            var $container = $("html,body");
            var $scrollTo = $('.webform-client-form > div > .form-group:visible').last();

            $container.animate({scrollTop: $scrollTo.offset().top - $container.offset().top, scrollLeft: 0},300);
            
        });
        
        function activatePopover() {
            var originalLeave = $.fn.popover.Constructor.prototype.leave;
            $.fn.popover.Constructor.prototype.leave = function(obj){
              var self = obj instanceof this.constructor ?
                obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
              var container, timeout;
            
              originalLeave.call(this, obj);
            
              if(obj.currentTarget) {
                container = $(obj.currentTarget).siblings('.popover')
                timeout = self.timeout;
                container.one('mouseenter', function(){
                  //We entered the actual popover – call off the dogs
                  clearTimeout(timeout);
                  //Let's monitor popover content instead
                  container.one('mouseleave', function(){
                    $.fn.popover.Constructor.prototype.leave.call(self, self);
                  });
                })
              }
            };
            
            $('body').popover({ selector: '[data-popover]', trigger: 'click hover', placement: 'auto', delay: {show: 50, hide: 400}});
        }
        
        $( document ).ready(function() {
            setCPpadding ();
            setCoverHeight ();
            activatePopover();
            
            $(".html-popover").popover({
                html: true, 
	            content: function() {
                    return $('#popover-content').html();
                }
            });
            
            $(".i18n-uk #edit-search-api-views-fulltext").attr("placeholder", "Пошук...");
            //$("#block-views-exp-search-page .form-item-created-date input, #block-views-exp-search-page .form-item-created-1-date input").attr("placeholder", "03.07.2016");
        });

        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                setCPpadding ();
                setCoverHeight ();    
            }, 250);
        });
         
    });
}(jQuery));