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

accordionPro.backend = {
    settings: {
        addDialogUrl: '',
        addDialogTitle: '',
        editDialogUrl: '',
        editDialogTitle: '',
        getStylePresetsUrl: '',
        removePrompt: '',
        importDialogTitle: '',
        importDialogUrl: '',
        redactorFilemanager: null,
        redactorSitemap: null
    },
    
    currentIndex: 0,
    
    getNextIndex: function() {
        this.currentIndex++;
        
        return this.currentIndex;
    },
    
    textToHtml: function(text) {
        return "<p>" + text + "</p>";
    },
    
    appendDummyItem: function() {
        
        var title = "Lorem ipsum dolor amet";
        var paragraph = this.textToHtml("Praesent eget lorem vitae mauris interdum luctus convallis a nunc. Phasellus dictum placerat volutpat. Cras facilisis quam lacus, molestie bibendum nisi egestas nec. Quisque vitae velit posuere, faucibus libero a, posuere felis. Nullam sit amet ornare lectus. Etiam adipiscing elit viverra, tincidunt orci consequat, lacinia erat.");
        
        lastInsertId = this.appendItem(title, paragraph, 1);
        
        this.checkOpenState(lastInsertId);

        var $item = $("#itemsContainer").find("#item-" + lastInsertId);
        $("#itemsContainer .item").addClass("closed");
        $item.removeClass("closed");
        $item.find("input").focus();
    },
    
    appendEmptyItem: function() {
        var lastInsertId = this.appendItem("", "", 1);
        
        this.checkOpenState(lastInsertId);

        var $item = $("#itemsContainer").find("#item-" + lastInsertId);
        $("#itemsContainer .item").addClass("closed");
        $item.removeClass("closed");
        $item.find("input").focus();
    },
    
    updateNoItemsMessage: function() {
        var hasItems = $("#itemsContainer").html().trim().length === 0;
        
        if (hasItems) {
            $("#noItems").removeClass("hidden");
        } else {
            $("#noItems").addClass("hidden");
        }
    },

    appendItem: function(title, paragraph, isOpen) {
        var id = this.getNextIndex();
        
        $("#itemsContainer").append(Mustache.render($("#itemTemplate").html(), {
            id: id,
            title: title,
            paragraph: paragraph
        }));
        
        var $item = $("#itemsContainer").find("#item-" + id);
        
        var $select = $item.find("select");
        var $textEditor = $item.find("textarea");
        
        $select.val(isOpen);
        
        this.initRedactor($textEditor);
        this.bindEventHandlersForItemBox(id);
        this.updateNoItemsMessage();
        
        return id;
    },
    
    makeElementDraggable: function() {
        $("#itemsContainer").sortable({
            cancel: ".redactor-box, select, input, textarea"
        });
    },
    
    bindEventHandlersForItemBox: function(id) {
        var $item = $("#itemsContainer").find("#item-" + id).find("input");
        
        $item.bind("focus focusin", function() {
            $("#itemsContainer .item").addClass("closed");
            $(this).parent().parent().removeClass("closed");
        });
    },

    checkOpenState: function(activeId) {
        var collapsible = (parseInt($("#collapse").val()) === 1);
        
        if (collapsible === false) {
            var lastValue = $("#itemsContainer").find("#item-" + activeId + " select").val();
            
            $("#itemsContainer select").val(0);
            
            $("#itemsContainer").find("#item-" + activeId + " select").val(lastValue);
        }
    },

    collapseItems: function() {
        $("#itemsContainer .item").addClass("closed");
    },
    
    removeItem: function(id) {
        if (confirm(this.settings.removePrompt)) {
            $("#item-" + id).remove();
            
            this.updateNoItemsMessage();
        }
    },

    loadItems: function(items) {
        for(var i in items) {
            var item = items[i];
            this.appendItem(item.title, item.paragraph, item.isOpen);
        }
    },
    
    addPreset: function() {
        $.fn.dialog.open({
            href: this.settings.addDialogUrl,
            title: this.settings.addDialogTitle,
            width: '400',
            height: '500',
            modal: true
        });
    },
    
    initRedactor: function($el) {
        $el.redactor({
            minHeight: '100',
            'concrete5': {
                filemanager: this.settings.redactorFilemanager,
                sitemap: this.settings.redactorSitemap,
                lightbox: true
            },
            plugins: ['table']
        });
    },
    
    getSelectedPresetId() {
        return $("#stylePresetId").val();
    },
    
    refreshPresetList: function(selectedPresetId) {
        $.ajax({
            type: "GET",
            url: this.settings.getStylePresetsUrl,
            success: function(data) {
                if (typeof data.items !== "undefined") {
                    // remove old entries
                    $("#stylePresetId").find('option').remove();
                    
                    for(var presetId in data.items) {
                        var presetName = data.items[presetId];
                        
                        // add entry
                        $("#stylePresetId").append($('<option></option>').val(presetId).html(presetName));
                    }
                    
                    if (selectedPresetId > 0) {
                        // restore selected index
                        $("#stylePresetId").val(selectedPresetId);
                    }
                }
                
            }
        });
    },
    
    editPreset: function() {
        $.fn.dialog.open({
            href: this.settings.editDialogUrl + "/" + this.getSelectedPresetId(),
            title: this.settings.editDialogTitle ,
            width: '400',
            height: '500',
            modal: true
        });
    },
    
    importPresets: function() {
        $.fn.dialog.open({
            href: this.settings.importDialogUrl ,
            title: this.settings.importDialogTitle ,
            width: '400',
            height: '500',
            modal: true
        });
    },
    
    bindEventHandlers: function() {
        var self = this;
        
        $("#addPreset").bind("click", function() {
            self.addPreset();
        });
        
        $("#editPreset").bind("click", function() {
            self.editPreset();
        });
        
        $("#addItem").bind("click", function() {
            self.appendEmptyItem();
        });
        
        $("#importPresets").bind("click", function() {
            self.importPresets();
        });
        
        $("#addDummyItem").bind("click", function() {
            self.appendDummyItem();
        });
        
    },
    
    applySettings: function(settings) {
        this.settings = settings;
    },
    
    init: function(settings) {
        this.applySettings(settings);
        this.bindEventHandlers();
        this.makeElementDraggable();
        this.loadItems(settings.items);
        this.updateNoItemsMessage();
    }
};