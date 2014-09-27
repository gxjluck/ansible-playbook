<?php

/**
 * Temporary BetaFeature for HHVM.
 *
 * Thiswas sourced from TranslateWiki (https://github.com/wikimedia/translatewiki/blob/0b7ba4665ab3e599516d2c2774e2541495a9d7a4/TranslatewikiSettings.php#L478)
 */

$wgHooks['GetBetaFeaturePreferences'][] = function ( $user, &$prefs ) {
	$prefs['hhvm-beta'] = array(
		'label-message' => 'hhvm-beta-label',
		'desc-message' => 'hhvm-beta-desc',
		'info-link' => 'http://www.hhvm.com/',
		'discussion-link' => 'https://meta.orain.org/wiki/Forum:HHVM',
	);
};
$wgHooks['BeforePageDisplay'][] = function ( OutputPage $out ) {
	$req = $out->getRequest();
	$user = $out->getUser();
	$hasCookie = $req->getCookie( 'hhvm', '' );
	$wantsCookie = BetaFeatures::isFeatureEnabled( $user, 'hhvm-beta' );

	if ( !$hasCookie && $wantsCookie ) {
		$req->response()->setcookie( 'hhvm', '1', 0, array( 'prefix' => '' ) );
	}
	if ( $hasCookie && !$wantsCookie ) {
		$req->response()->setcookie( 'hhvm', '0', -1, array( 'prefix' => '' ) );
	}
};
$wgHooks['RecentChange_save'][] = function ( RecentChange $rc ) {
	if ( wfIsHHVM() ) {
		ChangeTags::addTags( 'HHVM', $rc->getAttribute( 'rc_id' ) );
	}
	return true;
};
$wgHooks['LocalisationCacheRecache'][] = function ( $cache, $code, &$cachedData ) {
	if ( $code === 'en' ) {
		$cachedData['messages']['hhvm-beta-label'] = 'HHVM';
		$cachedData['messages']['hhvm-beta-desc'] = 'HHVM is a new and faster PHP runtime.';
		$cachedData['messages']['tag-HHVM'] = '[[m:Forum:HHVM|HHVM]]';
		$cachedData['messages']['tag-HHVM-description'] = 'Changes made using HHVM. These are tagged for analysis purposes';
	}
};