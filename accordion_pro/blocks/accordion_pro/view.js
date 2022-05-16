/**
 * @project:   Accordion Pro add-on for concrete5
 * 
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0
 */


if (typeof accordionPro === "undefined") {
    var accordionPro = {};
}

accordionPro.frontend = {
    initAccordion: function($accordionContainer) {
        var $accordionTitle = $accordionContainer.find(".accordion-header");
        
        this.bindEventHandler($accordionTitle);
    },
    
    toogleAccordion: function($accordionItem) {
        var $accordionContainer = $accordionItem.parent();
        
        if (this.isItemOpen($accordionItem)) {
            this.closeAccordion($accordionItem);
        } else {
            if (this.isCollapsible($accordionContainer)) {
                this.closeOpenAccordions($accordionItem);
            }
            
            this.openAccordion($accordionItem);
        }
    },
    
    compareElements: function($a, $b) {
        return $a[0] === $b[0];
    },
    
    closeOpenAccordions: function($sourceItem) {
        var self = this;
        var $accordionContainer = $sourceItem.parent();
        
        $accordionContainer.find(".accordion-item").each(function() {
            var $currentItem = $(this);
            var isSourceItem = self.compareElements($currentItem, $sourceItem);
            
            if (isSourceItem === false) {
                self.closeAccordion($currentItem);
            }
        });
    },
    
    openAccordion: function($accordionItem) {
        var $accordionContainer = $accordionItem.parent();
        var anmationTime = this.getAnimationDuration($accordionContainer);
        var shouldScrollToActiveItem = this.shouldScrollToActiveItem($accordionContainer);
      
        $accordionItem.addClass("open");
          
        $accordionItem.find(".accordion-outer-content").slideDown(anmationTime, function() {
            if (shouldScrollToActiveItem) {
                $('html, body').animate({
                    scrollTop: $accordionItem.offset().top
                }, anmationTime);
            }
        });
    },
    
    closeAccordion: function($accordionItem) {
        var $accordionContainer = $accordionItem.parent();
        var anmationTime = this.getAnimationDuration($accordionContainer);

        $accordionItem.find(".accordion-outer-content").slideUp(anmationTime, function() {
            $accordionItem.removeClass("open");
        });
    },
    
    isItemOpen: function($accordionItem) {
        return $accordionItem.hasClass("open");
    },
    
    isCollapsible: function($accordionContainer) {
        return parseInt($accordionContainer.data("collapse")) === 0;
    },
    
    shouldScrollToActiveItem: function($accordionContainer) {
        return parseInt($accordionContainer.data("scrollToActiveItem")) === 1;
    },
    
    getAnimationDuration: function($accordionContainer) {
        return parseInt($accordionContainer.data("animationDuration"));
    },
    
    bindEventHandler: function($accordionTitle) {
        var self = this;
        
        $accordionTitle.bind("click", function() {
            var $accordionItem = $(this).parent();
            
            self.toogleAccordion($accordionItem);
        });
    },
    
    init: function () {
        var self = this;
        
        $('.accordion-container').each(function() {
            self.initAccordion($(this));
        });
    }
};

$(document).ready(function () {
    accordionPro.frontend.init();
});



//COMPLIANT FAQ ACCORDION CONTROLS//


$(document).ready(function() {
    function uuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/x|y/g, function(xy) {
            var r = Math.floor(Math.random() * 16);
            return (xy === 'x' ? r : (r & 3 | 8)).toString(16);
        });
    }

    $('#faq_acc .accordion-header-inner').each(function(i, e) {
        var $e = $(e);
        var name = $e.text();
        var id = uuid();
        $e.after('<button href="#" class="open-menu3" aria-expanded="false"></button>');
        $e.nextAll('button:first').attr('aria-label', 'Open ' + name + ' menu').attr('aria-controls', id);
        $e.nextAll('ul:first').attr('id', id);
    });

    $(".open-menu3").on('click', function(e) {
        e.preventDefault();
        var $target = $(e.target);
        if ($target.attr('aria-expanded') === 'true') {
            $target.attr('aria-expanded', 'false');
            //$target.text('+');
            return;
        } else {
            //$target.text('-');
        }

        // Close any other open menus, unless they are parents of this one.
        var parentMenus = $target.parentsUntil('#faq_acc').filter('ul').prev('button.open-menu3');
        $('#faq_acc button.open-menu3[aria-expanded=true]').not(parentMenus).attr('aria-expanded', 'false');
        $target.attr('aria-expanded', 'true');
    });
});


$(document).ready(function() {
    $('li.nav-path-selected').each(function() {
        if ($(this).find('ul').length > 0) {
            $(this).addClass('open_drop');
        }
    });

    /*$("#faq_acc > .top_level.unselected.nav-dropdown > div").click(function(e) {
        if ($(this).parent().hasClass('open_drop')) {
            $(".top_level.unselected.nav-dropdown").removeClass('open_drop');
            $(".top_level.unselected.nav-dropdown > ul").slideUp();
        } else {
            $(".top_level.unselected.nav-dropdown").removeClass('open_drop');
            $(".top_level.unselected.nav-dropdown > ul").slideUp();
            $(this).parent().find('ul:first').each(function() {
                $(this).slideDown('slow');
            });
            $(this).parent().addClass('open_drop');
        }
    });
    $(".lower_level > .top_level.unselected.nav-dropdown > div").click(function(e) {
        if ($(this).parent().hasClass('open_drop')) {
            $(".lower_level > .top_level.unselected.nav-dropdown").removeClass('open_drop');
            $(".lower_level > .top_level.unselected.nav-dropdown > ul").slideUp();
        } else {
            $(".lower_level > .top_level.unselected.nav-dropdown").removeClass('open_drop');
            $(".lower_level > .top_level.unselected.nav-dropdown > ul").slideUp();
            $(this).parent().find('ul:first').each(function() {
                $(this).slideDown('slow');
            });
            $(this).parent().addClass('open_drop');
        }
    });*/

});