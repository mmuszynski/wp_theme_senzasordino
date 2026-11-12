/**
 * Close the masthead issue dropdown when clicking or tapping outside it.
 */
( function () {
	function closeIssueMenus() {
		document.querySelectorAll( 'details.masthead-issue-nav[open]' ).forEach( function ( menu ) {
			menu.removeAttribute( 'open' );
		} );
	}

	document.addEventListener( 'click', function ( event ) {
		if ( ! event.target.closest( 'details.masthead-issue-nav' ) ) {
			closeIssueMenus();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			closeIssueMenus();
		}
	} );
}() );
