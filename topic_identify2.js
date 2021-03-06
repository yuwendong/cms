var topic = QUERY;
if(topic == '中国'){
  var start_ts = 1377964800 + 900;
}
else{
  var start_ts = START_TS;
}
var end_ts = END_TS;
var network_type1 = 1;//源头转发网络为1，直接上级转发网络为2
var previous_data = null;
var current_data = null;
var networkShowed = 0;
var networkUpdated = 0;
var animation = 0;
var sigInst = null;
var animation_timer = null;
var quota={};
var networkdata ;
var rankdata;
var node;
var y_data;


// Date format
Date.prototype.format = function(format) { 
    var o = { 
  "M+" : this.getMonth()+1, //month 
  "d+" : this.getDate(),    //day 
  "h+" : this.getHours(),   //hour 
  "m+" : this.getMinutes(), //minute 
  "s+" : this.getSeconds(), //second 
  "q+" : Math.floor((this.getMonth()+3)/3),  //quarter 
  "S" : this.getMilliseconds() //millisecond 
    } 
    if(/(y+)/.test(format)) 
  format=format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
    for(var k in o)
  if(new RegExp("("+ k +")").test(format)) 
      format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length)); 
    return format; 
}

/**
 * This is an example on how to use sigma filters plugin on a real-world graph.
 */
var filter;

/**
 * DOM utility functions
 */
var _ = {
  $: function (id) {
    return document.getElementById(id);
  },

  all: function (selectors) {
    return document.querySelectorAll(selectors);
  },

  removeClass: function(selectors, cssClass) {
    var nodes = document.querySelectorAll(selectors);
    var l = nodes.length;
    for ( i = 0 ; i < l; i++ ) {
      var el = nodes[i];
      // Bootstrap compatibility
      el.className = el.className.replace(cssClass, '');
    }
  },

  addClass: function (selectors, cssClass) {
    var nodes = document.querySelectorAll(selectors);
    var l = nodes.length;
    for ( i = 0 ; i < l; i++ ) {
      var el = nodes[i];
      // Bootstrap compatibility
      if (-1 == el.className.indexOf(cssClass)) {
        el.className += ' ' + cssClass;
      }
    }
  },

  show: function (selectors) {
    this.removeClass(selectors, 'hidden');
  },

  hide: function (selectors) {
    this.addClass(selectors, 'hidden');
  },

  toggle: function (selectors, cssClass) {
    var cssClass = cssClass || "hidden";
    var nodes = document.querySelectorAll(selectors);
    var l = nodes.length;
    for ( i = 0 ; i < l; i++ ) {
      var el = nodes[i];
      //el.style.display = (el.style.display != 'none' ? 'none' : '' );
      // Bootstrap compatibility
      if (-1 !== el.className.indexOf(cssClass)) {
        el.className = el.className.replace(cssClass, '');
      } else {
        el.className += ' ' + cssClass;
      }
    }
  }
};
function updatePane (graph, filter) {
  // get max degree
  var maxDegree = 0,
      maxPagerank = 0,
      categories = {};
  
  // read nodes
  graph.nodes().forEach(function(n) {
    maxDegree = Math.max(maxDegree, graph.degree(n.id));
    maxPagerank = Math.max(maxPagerank, n.attributes.pagerank);
    if(n.attributes.acategory in categories){
        categories[n.attributes.acategory] += 1;
    }
    else{
        categories[n.attributes.acategory] = 1;
    }
  });

  var categoriesSorted = Object.keys(categories).sort(function(a, b){
      return categories[b] - categories[a]
  });
  var categoriesSortedTop10 = categoriesSorted.slice(0, 10);
  
  var cluster_colors = ['#CF0072', '#ED1B24', '#F15A25', '#F8931F', '#FBB03B', '#FDEE21', '#8CC63E', '#009345', '#0171BD', '#2D2F93'];
  var clusterid2color = {};
  for(var i=0; i<cluster_colors.length; i+=1 ){
      clusterid2color[categoriesSortedTop10[i]] = cluster_colors[i];
  }
  function contains(a, obj) {
      for (var i = 0; i < a.length; i++) {
          if (a[i] === obj) {
              return true;
          }
      }
      return false;
  }
  graph.nodes().forEach(function(n) {
      if(contains(categoriesSortedTop10, n.attributes.acategory)){
          n.color = clusterid2color[n.attributes.acategory];
      }
      else{
          n.color = '#11c897';
      }
  });

  // min degree
  _.$('min-degree').max = maxDegree;
  _.$('max-degree-value').textContent = maxDegree;

  _.$('min-pagerank').max = maxPagerank * 100000000;
  _.$('max-pagerank-value').textContent = maxPagerank * 100000000;
  
  // node category
  var nodecategoryElt = _.$('node-category');
  // Object.keys(categories).forEach(function(c) {
  categoriesSortedTop10.forEach(function(c) {
    var optionElt = document.createElement("option");
    optionElt.text = c;
    nodecategoryElt.add(optionElt);
  });

  // reset button
  _.$('reset-btn').addEventListener("click", function(e) {
    _.$('min-degree').value = 0;
    _.$('min-degree-val').textContent = '0';
    _.$('min-pagerank').value = 0;
    _.$('min-pagerank-val').textContent = '0';
    _.$('node-category').selectedIndex = 0;
    filter.undo().apply();
  });
}

function draw_animation() {
    if (start_ts > end_ts) {
        if (animation_timer)
            clearInterval(animation_timer);
    }
    else {
        sigInst.iterNodes(function(n){
            var timestamp = 0;
            for (var i=0;i<n.attr['attributes'].length;i++) {
                if (n.attr['attributes'][i]['attr'] == 'timestamp')
                    timestamp = parseInt(n.attr['attributes'][i]['val']);
            }
            if (timestamp < start_ts)
                n.hidden = 0;
        }).draw(2, 2, 2);
        start_ts = start_ts + 24 * 60 * 60;
    }
}

$("input[name='linLogModeRadios1']").on("click", function(){
    $('#linLogModeInput').val($(this).val());
});
$("input[name='outboundAttractionRadios1']").on("click", function(){
    $('#outboundAttractionInput').val($(this).val());
});
$("input[name='adjustSizesRadios1']").on("click", function(){
    $('#adjustSizesInput').val($(this).val());
});
$("input[name='strongGravityModeRadios1']").on("click", function(){
    $('#strongGravityModeInput').val($(this).val());
});

function change_edgeWeightInfluence(){
    $('#edgeWeightInfluence_span').html($('#edgeWeightInfluence_input').val());
}

function change_scalingRatio(){
    $('#scalingRatio_span').html($('#scalingRatio_input').val());
}

function change_gravity(){
    $('#gravity_span').html($('#gravity_input').val());
}

function change_slowdown(){
    $('#slowdown_span').html($('#slowdown_input').val());
}

function network_request_callback(data) {
    $("#network_progress").removeClass("active");
    $("#network_progress").removeClass("progress-striped");
    networkUpdated = 1;

    if (data) {
        $("#loading_network_data").text("计算完成!");
        $("#sigma-graph").show();

        sigma.parsers.gexf(data, {
            container: 'sigma-graph',
            settings: {
                drawEdges: true,
                edgeColor: 'default',
                defaultEdgeColor: '#ccc',
                defaultNodeColor: '#11c897'
            }
        },
            function(s) {
              // Initialize the Filter API
              filter = new sigma.plugins.filter(s);

              updatePane(s.graph, filter);

              function applyMinDegreeFilter(e) {
                var v = e.target.value;
                _.$('min-degree-val').textContent = v;

                filter
                  .undo('min-degree')
                  .nodesBy(function(n) {
                    return this.degree(n.id) >= v;
                  }, 'min-degree')
                  .apply();
              }

              function applyCategoryFilter(e) {
                var c = e.target[e.target.selectedIndex].value;
                filter
                  .undo('node-category')
                  .nodesBy(function(n) {
                    return !c.length || n.attributes.acategory === c;
                  }, 'node-category')
                  .apply();
              }

              function applyMinPagerankFilter(e) {
                var v = e.target.value;
                _.$('min-pagerank-val').textContent = v;

                filter
                  .undo('min-pagerank')
                  .nodesBy(function(n) {
                    return n.attributes.pagerank * 100000000 >= v;
                  }, 'min-pagerank')
                  .apply();
              }

              function applyZhibiaoCategoryFilter(e){
                var v = e.target.value;
                _.$('min-degree').value = 0;
                _.$('min-degree-val').textContent = '0';
                _.$('min-pagerank').value = 0;
                _.$('min-pagerank-val').textContent = '0';
                _.$('node-category').selectedIndex = 0;
                filter.undo().apply();
                if(v == 'degree'){
                    $('#min_degree_container').removeClass('hidden');
                    $('#min_pagerank_container').addClass('hidden');
                }
                if(v == 'pagerank'){
                    $('#min_pagerank_container').removeClass('hidden');
                    $('#min_degree_container').addClass('hidden');
                }
              }

              _.$('min-degree').addEventListener("input", applyMinDegreeFilter);  // for Chrome and FF
              _.$('min-degree').addEventListener("change", applyMinDegreeFilter); // for IE10+, that sucks
              _.$('min-pagerank').addEventListener("input", applyMinPagerankFilter);  // for Chrome and FF
              _.$('min-pagerank').addEventListener("change", applyMinPagerankFilter); // for IE10+, that sucks
              _.$('zhibiao-category').addEventListener("change", applyZhibiaoCategoryFilter);
              _.$('node-category').addEventListener("change", applyCategoryFilter);

              // Start the ForceAtlas2 algorithm:
              var linLogMode = ($('#linLogModeInput').val() === 'true');
              var outboundAttractionDistribution = ($('#outboundAttractionInput').val() === 'true');
              var adjustSizes = ($('#adjustSizesInput').val() === 'true');
              var strongGravityMode = ($('#strongGravityInput').val() === 'true');
              var edgeWeightInfluence = parseInt($('#edgeWeightInfluence_input').val());
              var scalingRatio = parseInt($('#scalingRatio_input').val());
              var gravity = parseInt($('#gravity_input').val());
              var slowDown = parseInt($('#slowdown_input').val());
              var config = {
                  'linLogMode': linLogMode,
                  'outboundAttractionDistribution': outboundAttractionDistribution,
                  'adjustSizes': adjustSizes,
                  'edgeWeightInfluence': edgeWeightInfluence,
                  'scalingRatio': scalingRatio,
                  'strongGravityMode': strongGravityMode,
                  'gravity': gravity,
                  'slowDown': slowDown
              }
              s.startForceAtlas2(config);

              $("#refresh_layout").click(function(){
                  //s.stopForceAtlas2();
                  var linLogMode = ($('#linLogModeInput').val() === 'true');
                  var outboundAttractionDistribution = ($('#outboundAttractionInput').val() === 'true');
                  var adjustSizes = ($('#adjustSizesInput').val() === 'true');
                  var strongGravityMode = ($('#strongGravityInput').val() === 'true');
                  var edgeWeightInfluence = parseInt($('#edgeWeightInfluence_input').val());
                  var scalingRatio = parseInt($('#scalingRatio_input').val());
                  var gravity = parseInt($('#gravity_input').val());
                  var slowDown = parseInt($('#slowdown_input').val());
                  var config = {
                      'linLogMode': linLogMode,
                      'outboundAttractionDistribution': outboundAttractionDistribution,
                      'adjustSizes': adjustSizes,
                      'edgeWeightInfluence': edgeWeightInfluence,
                      'scalingRatio': scalingRatio,
                      'strongGravityMode': strongGravityMode,
                      'gravity': gravity,
                      'slowDown': slowDown
                  }
                  s.configForceAtlas2(config);
                  s.startForceAtlas2();
                  s.refresh();
              });

              $("#pause_layout").click(function(){
                  s.stopForceAtlas2();
              });

              $("#stop_layout").click(function(){
                  s.killForceAtlas2();
              });

              /*
              $('#community_detail_a').click(function(){
                  var community_id = $('#community').html();
                  var community_nodes = [];
                  s.graph.nodes().forEach(function(n) {
                      if(String(n.attributes.acategory) == String(community_id)){
                          community_nodes.push(n);
                      }
                  });
                  community_nodes.sort(function(a, b){
                      return parseFloat(a.attributes.pagerank) - parseFloat(b.attributes.pagerank)
                  });
                  var top_nodes = community_nodes.slice(community_nodes.length-3, community_nodes.length);
                  top_nodes.reverse();
                  refresh_important_nodes(top_nodes);
              });

              function refresh_important_nodes(nodes){
                  $("#group_user_list").empty();
                  var html = "";
                  for(var n in nodes){
                      console.log(n);
                      $("#group_user_list").append("<span>" + n.attributes.name + "(" + n.attributes.pagerank + ")于" + n.attributes.timestamp + " 发布 " + n.attributes.text + "</span>");
                  }
              }
              
              $('#neighbourhood_detail_a').click(function(){
                  var community_id = $('#community').val();
                  var community_nodes = [];
                  s.graph.nodes().forEach(function(n) {
                      if(n.attributes.acategory == parseInt(community_id)){
                          community_nodes.push(n);
                      }
                  });
              });
              */

                // We first need to save the original colors of our
                // nodes and edges, like this:
                s.graph.nodes().forEach(function(n) {
                  n.originalColor = n.color;
                });
                s.graph.edges().forEach(function(e) {
                  e.originalColor = e.color;
                });

                // When a node is clicked, we check for each node
                // if it is a neighbor of the clicked one. If not,
                // we set its color as grey, and else, it takes its
                // original color.
                // We do the same for the edges, and we only keep
                // edges that have both extremities colored.
                s.bind('clickNode', function(e) {
                  var nodeId = e.data.node.id,
                      neighbor_graph = s.graph.neighborhood(nodeId),
                      toKeep = {},
                      node = e.data.node;

                  var node_uid = node.label;
                  var node_name = node.attributes.name;
                  var node_location = node.attributes.location;
                  var node_pagerank = node.attributes.pagerank;
                  var node_community = node.attributes.acategory;
                  var node_text = node.attributes.text;
                  var node_reposts_count = node.attributes.reposts_count;
                  var node_comments_count = node.attributes.comments_count;
                  var node_timestamp = node.attributes.timestamp;
                  var node_rank_pr = node.attributes.rank_pr;
                  var graph_type = 1; 

                  $('#nickname').html('<a target="_blank" href="http://weibo.com/u/' + node_uid + '">' + node_name + '</a>');
                  $('#location').html(node_location);
                  $('#pagerank').html(new Number(node_pagerank).toExponential(2) + ' ( 排名:' + node_rank_pr + ' )');
                  //$('#weibo_created_at').html(node_timestamp);
                  //$('#weibo_text').html(node_text);
                  //$('#weibo_reposts_count').html(node_reposts_count);
                  //$('#weibo_comments_count').html(node_comments_count);
                  // console.log(node_uid);
                  // console.log(node_community);
                  $('#community_detail_a').html('<button onclick="network_uid_community(' + node_community +','+ node_uid +',' + graph_type +')">' + '社团' + '</button>');
                  $('#user_weibo').html('<button onclick="network_weibolist(' + node_uid + ',' + graph_type +')">' + '微博' + '</button>');
                  $('#neighbourhood_detail_a').html('<button onclick="network_uid_neighbor(' + node_uid + ',' + graph_type +')">' + '邻居' + '</button>');

                  neighbor_graph.nodes.forEach(function(n){
                      toKeep[n.id] = n; 
                  });
                  toKeep[nodeId] = e.data.node;

                  s.graph.nodes().forEach(function(n) {
                    if (toKeep[n.id])
                      n.color = n.originalColor;
                    else
                      n.color = '#eee';
                  });

                  s.graph.edges().forEach(function(e) {
                    if (toKeep[e.source] && toKeep[e.target])
                      e.color = e.originalColor;
                    else
                      e.color = '#eee';
                  });

                  // Since the data has been modified, we need to
                  // call the refresh method to make the colors
                  // update effective.
                  s.refresh();
                });

                // When the stage is clicked, we just color each
                // node and edge with its original color.
                s.bind('clickStage', function(e) {
                  s.graph.nodes().forEach(function(n) {
                    n.color = n.originalColor;
                  });

                  s.graph.edges().forEach(function(e) {
                    e.color = e.originalColor;
                  });

                  // Same as in the previous event:
                  s.refresh();
                });
        });
    }

    else {
        $("#loading_network_data").text("暂无结果!");
    }

}

function show_network() {
    networkShowed = 0;
    network_type2 = 'source_graph'
    if (!networkShowed) {
        $("#network").height(610);
        $("#loading_network_data").css("display", "block");
        $("#network").removeClass('out');
        $("#network").addClass('in');
        networkShowed = 0;
        if (!networkUpdated){
            $.ajax({
                url: "/identify/graph/?topic=" + topic +'&start_ts=' + start_ts +'&end_ts='+end_ts+'&network_type='+network_type2,
                dataType: "xml",
                type: "GET",
                async: false,

                success: function (data) {
                    networkdata = data;
                    network_request_callback(data);
                },
                error: function(result) {
                    $("#loading_network_data").text("暂无结果!");
                }
            })
        }
   }
 else {
          networkShowed = 0;
          $("#network").removeClass('in');
           $("#network").addClass('out');
 }
}


function filter_node_in_network(node_uid){
    show_network();
    filter
      .undo('filter_node')
      .nodesBy(function(n) {
        return n.label == String(node_uid);
      }, 'filter_node')
      .apply();
}




function identify_request() {
  var topn = 100;
  $.get("/identify/rank/", {'topic': topic, 'start_ts': start_ts, 'end_ts': end_ts ,"topn" : topn, "network_type":network_type1}, request_callback, "json");
}


identify_request();



