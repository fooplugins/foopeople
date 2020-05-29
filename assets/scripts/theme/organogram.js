/**
 * jQuery org-chart/tree plugin.
 *
 * Author: Wes Nolte
 * http://twitter.com/wesnolte
 *
 * Based on the work of Mark Lee
 * http://www.capricasoftware.co.uk 
 *
 * Copyright (c) 2011 Wesley Nolte
 * Dual licensed under the MIT and GPL licenses.
 *
 */
(function($) {

  var nodeCount     = 0,
      expandClass   = 'ppl__node-expanded',
      collapseClass = 'ppl__node-collapsed',
      nodeClass     = 'ppl__node',
      hiddenClass   = 'ppl__hidden',
      tableClass    = 'ppl__table-organogram',
      trClass       = 'ppl__tr-cells',
      tdClass       = 'ppl__td-cell';
 

  $.fn.jOrgChart = function(options) {
    var opts      = $.extend({}, $.fn.jOrgChart.defaults, options),
        $appendTo = $(opts.chartElement),
        containerWidth, 
        tableWidth,
        $this     = $(this),
        $container = $("<div class='" + opts.chartClass + "'/>");

    // build the tree
    if($this.is("ul")) {
      buildNode($this.find("li:first"), $container, 0, opts);
    } else if($this.is("li")) {
      buildNode($this, $container, 0, opts);
    }
    $appendTo.append($container);

    // Scale the table to fit
    tableWidth = $container[0].scrollWidth;
    resizeTable($container, tableWidth);
    // Handle window resizing
    window.onresize = function() {
        resizeTable($container, tableWidth);
    };

  };

  // Option defaults
  $.fn.jOrgChart.defaults = {
    chartElement : 'body',
    depth      : -1,
    chartClass : "jOrgChart",
  };

  function resizeTable($container, tableWidth) {
    containerWidth = $container[0].clientWidth,
    containerHeight = $container[0].clientHeight,
    zoomLevel = containerWidth / tableWidth;

    $('.'+tableClass).css("transform", "scale("+zoomLevel+")");
    $container.css('height', containerHeight*zoomLevel);
  }

  // Method that recursively builds the tree
  function buildNode($node, $appendTo, level, opts) {
    var $table = $("<table/>");
    var $tbody = $("<tbody/>");
    if(nodeCount === 0) {
        $table.addClass(tableClass); 
    }

    // Construct the node container(s)
    var $nodeRow = $("<tr/>").addClass(trClass);
    var $nodeCell = $("<td/>").addClass(tdClass).attr("colspan", 2);
    var $childNodes = $node.children("ul:first").children("li");
    var $nodeDiv;
    
    if($childNodes.length > 1) {
      $nodeCell.attr("colspan", $childNodes.length * 2);
    }
    // Draw the node
    // Get the contents - any markup except li and ul allowed
    var $nodeContent = $node.clone().children("ul,li").remove().end().html();
	
      //Increaments the node count which is used to link the source list and the org chart
  	nodeCount++;
  	$node.data("tree-node", nodeCount);
  	$nodeDiv = $("<div>").addClass(nodeClass).data("tree-node", nodeCount).append($nodeContent);

    // Expand and contract nodes
    if ($childNodes.length > 0) {
      $nodeDiv.click(function() {
          var $this = $(this);
          var $tr = $this.closest("tr");

          if($tr.hasClass(collapseClass)) {
            $this.addClass('ppl__expand-up').removeClass('ppl__expand-down');
            $tr.removeClass(collapseClass).addClass(expandClass);
            $tr.nextAll("tr").toggleClass(hiddenClass);

            // Update the <li> appropriately so that if the tree redraws collapsed/non-collapsed nodes
            // maintain their appearance
            $node.removeClass('collapsed');
          } else{
            $this.addClass('ppl__expand-down').removeClass('ppl__expand-up');
            $tr.removeClass(expandClass).addClass(collapseClass);
            $tr.nextAll("tr").toggleClass(hiddenClass);

            $node.addClass('collapsed');
          }
        });
    }
    
    $nodeCell.append($nodeDiv);
    $nodeRow.append($nodeCell);
    $tbody.append($nodeRow);

    if($childNodes.length > 0) {
      // if it can be expanded then change the cursor
      $nodeDiv.addClass('ppl__expand-up').removeClass('ppl__expand-down');
    
      // recurse until leaves found (-1) or to the level specified
      if(opts.depth == -1 || (level+1 < opts.depth)) { 
        var $downLineRow = $("<tr/>");
        var $downLineCell = $("<td/>").attr("colspan", $childNodes.length*2);
        $downLineRow.append($downLineCell);
        
        // draw the connecting line from the parent node to the horizontal line 
        $downLine = $("<div></div>").addClass("line down");
        $downLineCell.append($downLine);
        $tbody.append($downLineRow);

        // Draw the horizontal lines
        var $linesRow = $("<tr/>");
        $childNodes.each(function() {
          var $left = $("<td></td>").addClass("line left top");
          var $right = $("<td></td>").addClass("line right top");
          $linesRow.append($left).append($right);
        });

        // horizontal line shouldn't extend beyond the first and last child branches
        $linesRow.find("td:first").removeClass("top").end().find("td:last").removeClass("top");

        $tbody.append($linesRow);
        var $childNodesRow = $("<tr/>");
        $childNodes.each(function() {
           var $td = $("<td class='node-container'/>");
           $td.attr("colspan", 2);
           // recurse through children lists and items
           buildNode($(this), $td, level+1, opts);
           $childNodesRow.append($td);
        });

      }
      $tbody.append($childNodesRow);
    }

    // any classes on the LI element get copied to the relevant node in the tree
    // apart from the special 'collapsed' class, which collapses the sub-tree at this point
    if ($node.attr('class') != undefined) {
        var classList = $node.attr('class').split(/\s+/);
        $.each(classList, function(index,item) {
            if (item == 'collapsed') {
                console.log($node);
                $nodeRow.nextAll('tr').addClass(hiddenClass);
                $nodeRow.removeClass(expandClass);
                $nodeRow.addClass(collapseClass);
                $nodeDiv.addClass('ppl__expand-down').removeClass('ppl__expand-up');
            } else {
                $nodeDiv.addClass(item);
            }
        });
    }

    $table.append($tbody);
    $appendTo.append($table);
    
    /* Prevent trees collapsing if a link inside a node is clicked */
    $nodeDiv.children('a').click(function(e){
        console.log(e);
        e.stopPropagation();
    });
  };

})(jQuery);