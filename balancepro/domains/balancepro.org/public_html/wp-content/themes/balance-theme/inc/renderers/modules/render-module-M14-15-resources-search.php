<?php

function render_m14_15_resources_search( $is_life_stage = false, $is_life_experience = false ) {
	$output = '<section aria-label="search container">
        <div ng-app="searchfilter" ng-controller="searchCtrl" class="searchContainer" ' . ( is_numeric($is_life_stage) ? (' data-life-stage="' . $is_life_stage . '" ') : '' ) .  ' ' . ( is_numeric($is_life_experience) ? (' data-life-experience="' . $is_life_experience . '" ') : '' ) .  '>
          <div id="searchSpinner" ng-if="searching"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> ' . __( 'Searching...', 'balance' ) . '</div>
          <div class="container background-white">
            <div class="row">
              <div class="inner-row">
                <aside id="sidebar" class="col-sm-4">
                  <div class="opener-block text-center"><a href="#" class="filter-drop-opener text-warning">' . __('VIEW FILTERS', 'balance' ) . '</a></div>
                  <div class="aside-drop">
                    <div class="ovh-holder">
                      <div class="aside-holder">
                        <div class="apply-block visible-xs">
                        	<a href="#" ng-click="resetFilters($event)" class="filter-drop-close">' . __('CANCEL', 'balance' ) . '</a>
                        	<a href="#" ng-click="apply($event)" class="filter-drop-close">' . __('APPLY', 'balance' ) . '</a>
                        </div>
                        <h2 class="aside-title hidden-xs text-center h3">' . __('FILTERS', 'balance' ) . '</h2>
                        <h3 class="aside-title-xs visible-xs text-center">' . __('Select Filters', 'balance' ) . '</h3>
                        <form class="aside-form">
                          <fieldset>
                            <ul class="aside-filter same-height-holder">
                              <li><a href="#" class="filter-opener visible-xs">' . __('RESOURCE TYPES', 'balance' ) . '<span ng-bind="selected.type + \' ' . __('selected', 'balance' ) . '\'" class="selected"></span><span class="custom-caret visible-xs"><img width="19" height="11" alt="image description" src="' . get_stylesheet_directory_uri() . '/images/drop-arrow.svg"></span></a><span class="filter-opener hidden-xs">' . __('RESOURCE TYPES', 'Types').'</span>
                                <ul class="filter-drop">
                                  <li>
                                    <div class="col-sm-12">
                                      <div class="input-group">
                                        <label for="select-all-types">
                                          <input id="select-all-types" type="checkbox" ng-change="toggleAll(\'type\')" ng-model="filters.type._all"><span class="fake-input"></span><span class="fake-label text-center"><span class="text">' . __('All Types', 'balance').'</span></span>
                                        </label>
                                      </div>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="col-xs-6">
                                      <div ng-repeat="type in attributes.type track by type.id" ng-if="$even" class="input-group">
                                        <label for="cb-{{type.tag}}">
                                          <input id="cb-{{type.tag}}" type="checkbox" ng-change="toggle(\'type\', type.tag)" ng-model="filters.type[type.tag]"><span class="fake-input"></span><span class="fake-label same-height"><span class="icon-{{type.tag}}"></span><span ng-bind="(type.tag | capitalize) + \'s \'" class="text"></span></span>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-xs-6">
                                      <div ng-repeat="type in attributes.type track by type.id" ng-if="$odd" class="input-group">
                                        <label for="cb-{{type.tag}}">
                                          <input id="cb-{{type.tag}}" type="checkbox" ng-change="toggle(\'type\', type.tag)" ng-model="filters.type[type.tag]"><span class="fake-input"></span><span class="fake-label same-height"><span class="icon-{{type.tag}}"></span><span ng-bind="(type.tag | capitalize) + \'s \'" class="text"></span></span>
                                        </label>
                                      </div>
                                    </div>
                                  </li>
                                </ul>
                              </li>
                              <li class="autocomplete-tag-holder"><a href="#" class="filter-opener visible-xs">' . __('TAGS', 'balance').'<span ng-bind="selected.tag + \' ' . __('selected', 'balance' ) . '\'" class="selected"></span><span class="custom-caret visible-xs"><img width="19" height="11" alt="image description" src="' . get_stylesheet_directory_uri() . '/images/drop-arrow.svg"></span></a><span class="filter-opener hidden-xs">' . __('Tags', 'balance').'</span>
                                <ul class="filter-drop">
                                  <li>
                                    <div class="col-xs-12">
                                      <div class="input-group">
                                        <input type="search" aria-label="Enter Keywords field" placeholder="Enter Keywords" ng-model="autofield" ui-autocomplete="autoOpts" class="autocomplete-tag-input">
                                        <button class="search-btn"><span class="icon-search"></span></button>
                                      </div>
                                      <div class="autocomplete-tag-list"><span ng-repeat="tag in filters.tag" ng-click="toggleTag($event, tag)" class="tag"><span ng-bind="attributes.tag[tag].tag"></span><a href="#" class="close"></a></span></div>
                                    </div>
                                  </li>
                                </ul>
                              </li>
                              <li ng-show="!settings.lifeStage && !settings.lifeExperience"><a href="#" class="filter-opener visible-xs">' . __('LIFE STAGES', 'balance').'<span ng-bind="selected.stage + \' ' . __('selected', 'balance' ) . '\'" class="selected"></span><span class="custom-caret visible-xs"><img width="19" height="11" alt="image description" src="' . get_stylesheet_directory_uri() . '/images/drop-arrow.svg"></span></a><span class="filter-opener hidden-xs">' . __('LIFE STAGES', 'balance').'</span>
                                <ul class="filter-drop same-height-holder">
                                  <li>
                                    <div class="col-sm-12">
                                      <div class="input-group">
                                        <label for="select-stage-all">
                                          <input id="select-stage-all" type="checkbox" ng-change="toggleAll(\'stage\')" ng-model="filters.stage._all"><span class="fake-input"></span><span class="fake-label text-center"><span class="text">' . __('All Stages', 'balance').'</span></span>
                                        </label>
                                      </div>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="col-xs-6">
                                      <div ng-repeat="stage in attributes.stage track by stage.id" ng-if="$even" class="input-group">
                                        <label for="stage-{{stage.id}}" class="text-center label-text">
                                          <input id="stage-{{stage.id}}" type="checkbox" ng-change="toggle(\'stage\', stage.id)" ng-model="filters.stage[stage.id]"><span class="fake-input"></span><span class="fake-label"><span ng-bind="stage.tag" class="center text"></span></span>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-xs-6">
                                      <div ng-repeat="stage in attributes.stage track by stage.id" ng-if="$odd" class="input-group">
                                        <label for="stage-{{stage.id}}" class="text-center label-text">
                                          <input id="stage-{{stage.id}}" type="checkbox" ng-change="toggle(\'stage\', stage.id)" ng-model="filters.stage[stage.id]"><span class="fake-input"></span><span class="fake-label"><span ng-bind="stage.tag" class="center text"></span></span>
                                        </label>
                                      </div>
                                    </div>
                                  </li>
                                </ul>
                              </li>
                            </ul><a href="#" class="filter-drop-close overlay"></a>
                          </fieldset>
                        </form>
                      </div>
                    </div>
                  </div>
                </aside>
              </div>
              <div id="content" class="content-section col-sm-8">
                <form ng-submit="search()" class="sort-form">
                  <fieldset>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="search" aria-label="Enter Keywords field. Content section" placeholder="Enter Keywords" ng-model="filters.query" class="form-control" id="keyword-search">
                        <button type="submit" class="search-btn"><span class="icon-search"></span></button>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <select aria-label="Sort by" ng-change="apply()" ng-model="filters.sortBy" class="form-control sort-form-select">
                          <option value="" class="hide-me">' . __('Sort By', 'balance').'</option>
                          <option value="null" ng-selected="filters.sortBy==\'null\'">' . __('Relevance', 'balance').'</option>
                          <option value="-views" ng-selected="filters.sortBy==\'-views\'">' . __('Most Popular', 'balance').'</option>
                          <option value="-date" ng-selected="filters.sortBy==\'-date\'">' . __('Most Recent', 'balance').'</option>
                        </select>
                      </div>
                    </div>
                  </fieldset>
                </form>
                <div ng-class="{\'hidden\': firstLoad}" class="resource-column same-height-holder hidden">
                  <div ng-repeat="resource in resources|orderBy:(filters.sortBy||\'null\')|limitTo:perPage:((filters.page-1)*perPage)" class="col-sm-6 col-md-4">
                    <div class="resource-block"> <a ng-href="{{resource.url}}" target="_self">
                      <div class="img-holder same-height"><span class="icon-{{resource.post_type}}"></span></div>
                      <div class="text-holder">
                        <p ng-bind="resource.title" class="dot-holder"></p>
                        <div class="btn-tag same-height">
                        	<a ng-repeat="tag in resource.tags | limitTo: 3" ng-bind="attributes.tag[tag].tag" ng-click="toggleTag($event, tag)" class="tag"></a>
                        </div>
                        </a>
                      </div>
                      <span class="icon-lock" ng-if="resource.locked"></span>
                    </div>
                  </div>
                </div>
                <nav aria-label="balance pager m14-m15" balance-pager class="paging-holder clear"></nav>
              </div>
            </div>
          </div>
          <script type="text/ng-template" id="/pager.html">
            <ul ng-class="{\'hidden\': firstLoad}" class="pagination hidden">
              <li>
              	<a href="#" aria-label="' . __('Previous', 'balance').'" ng-click="setPage(filters.page-1)" ng-disabled="filters.page&lt;=1">
              		<span class="btn-prev"></span>
              		<span class="hidden-xs">' . __('Prev', 'balance').'</span>
              	</a>
              </li>
              <li ng-repeat="page in pager.pages" ng-class="{\'active\': page.num==filters.page, \'hidden-xs\': page.hidden}">
              	<a href="#" ng-click="setPage(page.num)" ng-bind="page.num"></a>
              </li>
              <li>
              	<a href="#" aria-label="' . __('Next', 'balance').'" ng-click="newPager = filters.page&gt;=pager.total ? filters.page : filters.page+1; setPage(newPager)" ng-disabled="filters.page&gt;=pager.total">
              		<span class="hidden-xs">' . __('Next', 'balance').'</span>
              		<span class="btn-next"></span>
              	</a>
              </li>
            </ul>
            <p>
            	<span>' . __('of', 'balance').'&nbsp;</span>
            	<span ng-bind="pager.total"></span>
            	<span>&nbsp;' . __('pages', 'balance').'</span>
            </p>
          </script>
        </div>
      </section>';


	$output .= ' <style>';
	$output .= '        .glyphicon-refresh-animate {';
	$output .= '            -animation: spin .7s infinite linear;';
	$output .= '            -webkit-animation: spin2 .7s infinite linear;';
	$output .= '        }';

	$output .= '        @-webkit-keyframes spin2 {';
	$output .= '            from { -webkit-transform: rotate(0deg);}';
	$output .= '            to { -webkit-transform: rotate(360deg);}';
	$output .= '        }';

	$output .= '        @keyframes spin {';
	$output .= '            from { transform: scale(1) rotate(0deg);}';
	$output .= '            to { transform: scale(1) rotate(360deg);}';
	$output .= '        }';
	$output .= '        #searchSpinner {';
	$output .= '            width: 7em;';
	$output .= '            margin: 0 auto;';
	$output .= '        }';
	$output .= '        @media (min-width: 700px) and (max-width: 767px) {';
  $output .= '          nav.navbar.hide-header {';
	$output .= '            display: none !important;';
	$output .= '          }';
	$output .= '        }';
	$output .= '    </style>';
	$output .= '<script>';
	$output .= "jQuery('#keyword-search').focus(function() {";
		$output .= "jQuery('nav.navbar').addClass('hide-header');";
	$output .= "});";
	$output .= "jQuery(this).focusout(function() {";
	  $output .= "jQuery('nav.navbar').removeClass('hide-header');";
	$output .= "});";
	$output .= '</script>';

	return stripslashes( $output );
}
