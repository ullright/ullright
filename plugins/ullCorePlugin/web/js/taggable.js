/**
 * taggableWidget.js
 * 
 * remark: most code is copied from http://www.symfony-project.org/plugins/sfDoctrineActAsTaggablePlugin
 *
 */

// Great new taggable widget that requires jQuery UI to be loaded.
// Enhances an existing text field (progressive enhancement). See
// the README

function taggableWidget(selector, options)
{
	$(selector).each(function()
	{	
		// Semi-global
		var typeaheadUrl = options['typeahead-url'];
		var tagsLabel = options['tags-label'];
		var popularTagsLabel = options['popular-tags-label'];
		var popularTags = options['popular-tags'];
		var existingTags = {};
		var allTags = options['all-tags'];
		var commitSelector = options['commit-selector'];
		var commitEvent = options['commit-event'];
		var addLinkClass = options['add-link-class'];
		var removeLinkClass = options['remove-link-class'];
		var selectedTagMarkup = options['selected_tag_markup'];
		var addTagLabel = options['add-tag-label'];

		if (typeof(tagsLabel) == 'undefined') 
		{
			tagsLabel = "Existing Tags";
		};
		if (popularTagsLabel === undefined)
		{
			popularTagsLabel = 'Popular Tags';
		}
		if (typeof(popularTags) == 'undefined')
		{
			popularTags = {};
		};
		if (typeof(allTags) == 'undefined')
		{
			allTags == {};
		};
		if (typeof(commitEvent) == 'undefined')
		{
			commitEvent = 'click';
		}
		if (addLinkClass === undefined)
		{
			addLinkClass = 'ull_widget_taggable_cloud';
		}
		if (removeLinkClass === undefined)
		{
			removeLinkClass = 'ull_widget_taggable_existing_tags';
		}
		
		if (addTagLabel === undefined)
		{
			addTagLabel = 'Add';
		}
		
		// populate existingTags
		if ($(this).val().trim() != '')
		{
			var lp = $(this).val().split(',');
			for (x in lp)
			{
				existingTags[lp[x].trim()] = lp[x].trim();
			}
		}


		function makePopularLink(attributes, title, text)
		{
			var new_tag = $('<span />');
			var new_link = $('<a />');
			var tag_size = '';
			
			if(text == 1)
			{
				tag_size = ' taggable_big';
			}
			else if(text == 2)
			{
				tag_size = ' taggable_bigger';
			}
			else if(text == -1)
			{
				tag_size = ' taggable_small';
			}
			else if(text == -2)
			{
				tag_size = ' taggable_smaller';
			}
				
			
			new_tag.attr({ title: title }).addClass('ull_widget_taggable_cloud_element' + tag_size);
			new_tag.prepend(new_link);
			new_link.text(title);
			new_link.attr(attributes);
			return new_tag;
		}

		function makeRemoveLink(attributes, title, text)
		{
			title = $.trim(title);

			if(selectedTagMarkup === undefined)
			{
				var new_tag = $('<span />');
				var new_link = $('<a />');
				var tagTitle = title;
	
				if (typeof(allTags) != 'undefined')
				{
					if (typeof(allTags[title]) != 'undefined')
					{
						title = title + '<span class="a-tag-count">(' + allTags[title] + ')</span>';
					}
					else
					{
						title = title + '<span class="a-tag-count">(0)</span>';
					}
				}
	
				new_tag.html("<span>"+title+"</span>");
				new_tag.attr({ title: tagTitle }).addClass('a-tag');
				new_link.text('Remove Tag');
				new_link.attr(attributes);
				new_link.attr({ title: 'Remove' }).addClass('a-btn icon a-close-small no-label no-bg');
				new_link.prepend('<span class="icon"></span>');
				new_tag.append(new_link);
				return new_tag;
			}
			else
			{
				return $(selectedTagMarkup.replace(/%s/g, title));
			}
		}

		function trimExcessCommas(string)
		{
			string = string.replace(/(^,)|(, ?$)/g, '');
			string = string.replace(/(,,)|(, ,)/, ',');
			string = $.trim(string);

			return string;
		}
		
		// For multiple word typeahead we need some helpers
		
		function split( val ) {
			return val.split( /,\s*/ );
		}
		
		function extractLast( term ) {
			return split( term ).pop();
		}
		
		function multipleSelect(event, ui)
		{
			var terms = split( this.value );
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push( ui.item.value );
			// add placeholder to get the comma-and-space at the end
			terms.push( "" );
			this.value = terms.join( ", " );
			return false;
		}
		
		function multipleFocus()
		{
			// prevent value inserted on focus
			return false;
		}
		
		function multipleSearch()
		{
			// custom minLength
			var term = extractLast( this.value );
			if ( term.length < 2 ) {
				return false;
			}
		}
		
		// We don't want to display popular tags that we're already using
		var unusedPopulars = {};
		for (x in popularTags)
		{
			if (typeof(existingTags[x]) == 'undefined')
			{
				unusedPopulars[x] = popularTags[x];
			}
		}		
		
		var popularsAttributes = {};
		var existingTagsAttributes = {};
		var existingDiv = $('<div />');
		var popularsDiv = $('<div />');

		// Establish the quick enhancement
		var tagInput = $(this);
		var typeAheadContainer = $('<div />');
		var typeAheadBox = $('<input />');
		var typeAheadBoxId = 'inline-tag-ahead-box-' + Math.floor(Math.random() * 2000);
		typeAheadBox.attr('type', 'text');
		typeAheadBox.attr('id', typeAheadBoxId);
		if ((typeof(allTags) == 'undefined') && (typeof(typeaheadUrl) != 'undefined'))
		{
			typeAheadBox.autocomplete({ 
			  source: function (request, response) {
					$.getJSON(typeaheadUrl, {
						  term: extractLast(request.term)
					  }, response);
				},
				search: multipleSearch,
				focus: multipleFocus,
				select: multipleSelect
			});
		}
		else if (typeof(allTags) != 'undefined')
		{
			var allTagsReformat = new Array();
			for (x in allTags)
			{
				allTagsReformat.push(x);
			}
			typeAheadBox.autocomplete({
				source: function (request, response) {
					response( $.ui.autocomplete.filter(
										allTagsReformat, extractLast( request.term ) ) );
				},
				search: multipleSearch,
				focus: multipleFocus,
				select: multipleSelect
			});
		}
		
		var addButton = $('<a />');
		addButton.html(addTagLabel);
		addButton.attr({'href' : '#', 'class' : 'ull_widget_taggable_add_tag', 'title' : addTagLabel});

		typeAheadContainer.addClass('ull_widget_taggable_type_ahead_box').append(typeAheadBox).append(addButton);

		tagInput.hide();
		tagInput.parent().prepend(typeAheadContainer);


		// Add a list of popular tags to be added
		function addTagsToForm(link)
		{
			var tag = link.attr('title');
			var value = tagInput.val() + ', ' + tag;

			value = trimExcessCommas(value);
			tagInput.val(value);
			
			if (link.parent().children().length == 2)
				link.parent().children('span.ull_widget_taggable_label').hide();
			link.remove();

			var new_link = makeRemoveLink(existingTagsAttributes, tag, tag + ' x');
			new_link.children('a').bind('click', function() { removeTagsFromForm($(this).parent()); return false; });
			existingDiv.append(new_link);
			existingDiv.children('span.ull_widget_taggable_label').show();
		}
		
		
		// Add a list of tags that may be removed
		function removeTagsFromForm(link)
		{
			var tag = link.attr('title');
			var value = tagInput.val();

			value = value.replace(tag, '');
			value = trimExcessCommas(value);
			tagInput.val(value);
			
			if (link.parent().children().length == 2)
				link.parent().children('span.ull_widget_taggable_label').hide();
			link.remove();
						
			// As we have just removed it from the list, we want the real deal populars.
			if (typeof(popularTags[tag]) != 'undefined')
			{
				var linkLabel = popularTags[tag];
				var new_link = makePopularLink(existingTagsAttributes, tag, linkLabel);
				new_link.children('span.ull_widget_taggable_label').bind('click', function() { addTagsToForm($(this).parent()); return false; });
				popularsDiv.children('span.ull_widget_taggable_label').show();
				popularsDiv.append(new_link);
			}
		}


		// a maker function for tag containers
		function makeTagContainer(containerLabel, tagArray, linkAttributes, linkLabelType)
		{
			// Add a list of tags that may be removed
			var tagContainer = $('<div />');
			//tagContainer.addClass('ull_widget_taggable');
			var header = $('<span />');
			header.text(containerLabel);
			header.addClass('ull_widget_taggable_label');
			tagContainer.append(header);
			
			if (objEmpty(tagArray))
			{
				header.hide();
			}
		
			var attributes = {};
			for (x in tagArray)
			{	
				var linkLabel = '';
				if (linkLabelType == 'add')
				{
					tagContainer.addClass(addLinkClass);
					
					linkLabel = tagArray[x];
					var new_link = makePopularLink(linkAttributes, x, linkLabel);
					new_link.children('a').bind('click', function() { addTagsToForm($(this).parent());  return false; });
				}
				else if (linkLabelType == 'remove')
				{
					tagContainer.addClass(removeLinkClass);
										
					linkLabel = 'x ' + x;
					var new_link = makeRemoveLink(linkAttributes, x, linkLabel);
					new_link.children('a').bind('click', function() { removeTagsFromForm($(this).parent());  return false; });
				}				
				tagContainer.append(new_link);
			}
			return tagContainer;
		}

		// Add the new tags to the existing form input.
		// If the user doesn't click Save changes or add... tough?
		function commitTagsToForm()
		{	
			if (typeAheadBox.val() != '')
			{
				var value = tagInput.val() + ',' + typeAheadBox.val();
				value = trimExcessCommas(value);
				tagInput.val(value);
				typeAheadBox.val('');
			
				//roundabout way to escape :)
				//needed to prevent injecting
				value = $('<div/>').text(value).html();
				
				existingTags = {};
				var lp = value.split(',');
				for (x in lp)
				{
					existingTags[lp[x]] = lp[x];
				}
			
				existingDiv.html(makeTagContainer(tagsLabel, existingTags, existingTagsAttributes, 'remove').html());
				existingDiv.find('a').each(function() {
					$(this).bind('click', function() {
						removeTagsFromForm($(this).parent());
						return false;
					});
				});
			}
			return false;
		}
		addButton.bind('click', function() { commitTagsToForm(); return false; });
		if (commitSelector != 'undefined')
		{
			$(commitSelector).bind(commitEvent, function() {
				commitTagsToForm();
				return true;
			});
		}
	
		existingDiv = makeTagContainer(tagsLabel, existingTags, existingTagsAttributes, 'remove');
		existingDiv.children('a').bind('click', function() { removeTagsFromForm($(this));  return false; });
		tagInput.parent().prepend(existingDiv);
		
		popularsDiv = makeTagContainer(popularTagsLabel, unusedPopulars, popularsAttributes, 'add');
		popularsDiv.children('a').bind('click', function() { addTagsToForm($(this));  return false; });
		tagInput.parent().append(popularsDiv);
			
		// Catch that enter key, baby!
		$(document).keyup(function(e)
			{
				if (e.keyCode == 13)
				{
					if (typeAheadBox.get(0) === $(document.activeElement).get(0))
					{
						e.preventDefault();						
					}
				}
			});
		
		$(document).keypress(function(e)
			{
				if (e.keyCode == 13)
				{
					if (typeAheadBox.get(0) === $(document.activeElement).get(0))
					{
						e.preventDefault();						
					}
				}
			});
		
		typeAheadBox.keyup(function(e)
			{
		 		if (e.keyCode == 13)
		 		{
					e.preventDefault();
		 			commitTagsToForm();
		 		}
		 	});
		
		
		function objEmpty(obj)
		{
			for (var prop in obj)
			{
				if (obj.hasOwnProperty(prop))
				{
					return false;
				}
			}
			
			return true;
		}
	});	
	
}

// DEPRECATED. Requires the old sfJqueryReloadedPlugin and generally is not as cool.

// You need to bring in jQuery first in order for this to work
//
// Call it like this:
// pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
//
// Or similar. Now all of your input elements with the input-tag class
// automatically gain the typeahead suggestion feature.
//
// If you're not using Symfony and sfDoctrineActAsTaggablePlugin, 
// pass your own URL that returns a <ul> containing <li>s with the
// FULL TEXT of what the ENTIRE tag string will be if the user takes
// that suggestion, with the new tag you're suggesting an <a> link
// to #. Then use CSS to hide (visibility: none) the part of the 
// <li> that is not in the <a>. Don't introduce any extra whitespace.

function pkTagahead(tagaheadUrl)
{
  $(function() {
    function getKey(event)
    {
      // Portable keycodes sigh
      return event.keyCode ? event.keyCode : event.which;
    }
    function setClick(target)
    {
      $(target).find('a').click(function(event)
      {
        // span contains ul contains li contains a
        var span = this.parentNode.parentNode.parentNode;
        var input = $(span).data("tag-peer");
        // Get text from the li
        var parent = this.parentNode;
        $(input).val($(parent).text());
        $(input).focus();
        return false;
      });
    }
    // Add suggestions span (you'll need to style that)
    $('input.tag-input').after("<div class='tag-suggestions'></div>");
    // Each tag field remembers its suggestions span...
    $('input.tag-input').each(function() 
    {
      $(this).data("tag-peer", $(this).next()[0]);
    });
    // And vice versa
    $('div.tag-suggestions').each(function() 
    {
      $(this).data("tag-peer", $(this).prev()[0]);
    });
    // Now we can really throw down
    $('input.tag-input').keyup(function(event) 
    {
      var key = getKey(event);
      // Tab key 
      if (key == 9)
      {
        var peer = $(this).data("tag-peer");
        var suggestions = $(peer).find("li"); 
        if (suggestions.length)
        {
          $(this).val($(suggestions[0]).text());
          $(this).focus();
        }
        // In any case don't insert the tab
        return false;
      }
      else
      {
        // Trigger ajax update of suggestions
      } 
    });
    $('input.tag-input').keypress(function(event) 
    {
      // Firefox 2.0 mac is stubborn and only allows cancel here
      // (we will still get the keyup and do the real work there)
      var key = getKey(event);
      if (key == 9)
      {
        // Don't insert tabs, ever
        return false;
      }
    });
    var lastValues = {};
    setInterval(function() 
    {
      // AJAX query for suggestions only when changes have taken place
      $('input.tag-input').each(function() 
      {
        var last = $(this).data('tag-last');  
        var value = $(this).val();
        var peer = $(this).data('tag-peer');
        if (last !== value)
        {
          $(this).data('tag-last', value);
          $.post(
            tagaheadUrl, 
            { 
              current: $(this).val() 
            },
            function(data, textStatus) 
            {
              $(peer).html(data);       
              setClick(peer);
            }
          );
        }
      });
    }, 200);
  });
}
