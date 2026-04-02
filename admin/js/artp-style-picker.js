/**
 * Almost Ready — Style Picker
 *
 * Renders the style selection grid using @wordpress/dataviews, matching the
 * same UI as the Site Editor's Templates and Patterns screens.
 *
 * @wordpress/dataviews is bundled (not externalized) because it is not
 * registered as a global script handle on plain admin pages.
 */

import { DataViews, filterSortAndPaginate } from '@wordpress/dataviews';
import { createElement as el, useState, createRoot } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import domReady from '@wordpress/dom-ready';

const { styles: styleData, applyUrl, nonce } = window.artpStylePicker;

/**
 * Scaled-down iframe preview of the actual page with this style applied.
 * 1000×800 px iframe scaled to 0.2 → 200×160 px visible area, centred.
 */
function StylePreview( { item } ) {
	return el(
		'div',
		{
			style: {
				position: 'relative',
				width: '100%',
				height: '160px',
				overflow: 'hidden',
			},
		},
		el( 'iframe', {
			src: item.previewUrl,
			style: {
				position: 'absolute',
				top: 0,
				left: 'calc(50% - 100px)',
				width: '1000px',
				height: '800px',
				border: 'none',
				pointerEvents: 'none',
				transform: 'scale(0.2)',
				transformOrigin: 'top left',
			},
			scrolling: 'no',
			tabIndex: -1,
			title: '',
		} )
	);
}

const fields = [
	{
		id: 'preview',
		label: __( 'Preview', 'almost-ready-temporary-page' ),
		render: ( { item } ) => el( StylePreview, { item } ),
		enableSorting: false,
		enableHiding: false,
	},
	{
		id: 'label',
		label: __( 'Style', 'almost-ready-temporary-page' ),
		getValue: ( { item } ) => item.label,
		render: ( { item } ) =>
			el(
				'span',
				null,
				item.label,
				item.isActive &&
					el(
						'span',
						{
							style: {
								marginLeft: '8px',
								background: '#2271b1',
								color: '#fff',
								fontSize: '10px',
								fontWeight: 600,
								textTransform: 'uppercase',
								letterSpacing: '0.05em',
								padding: '2px 6px',
								borderRadius: '3px',
								verticalAlign: 'middle',
							},
						},
						__( 'Active', 'almost-ready-temporary-page' )
					)
			),
		enableSorting: false,
		enableHiding: false,
		enableGlobalSearch: true,
	},
	{
		id: 'description',
		label: __( 'Description', 'almost-ready-temporary-page' ),
		getValue: ( { item } ) => item.description,
		enableSorting: false,
		enableHiding: false,
	},
];

/**
 * POST to admin-post.php to apply the chosen style.
 * Preserves existing page content — only the style class is swapped.
 */
function applyStyle( slug ) {
	const form = document.createElement( 'form' );
	form.method = 'POST';
	form.action = applyUrl;

	[
		[ 'action', 'artp_apply_style' ],
		[ 'artp_style', slug ],
		[ '_wpnonce', nonce ],
	].forEach( ( [ name, value ] ) => {
		const input = document.createElement( 'input' );
		input.type = 'hidden';
		input.name = name;
		input.value = value;
		form.appendChild( input );
	} );

	document.body.appendChild( form );
	form.submit();
}

const actions = [
	{
		id: 'apply',
		label: __( 'Apply Style', 'almost-ready-temporary-page' ),
		isPrimary: true,
		callback: ( items ) => applyStyle( items[ 0 ].slug ),
	},
];

function StylePicker() {
	const [ view, setView ] = useState( {
		type: 'grid',
		search: '',
		page: 1,
		perPage: 20,
		titleField: 'label',
		mediaField: 'preview',
		fields: [ 'description' ],
	} );

	const { data: filtered, paginationInfo } = filterSortAndPaginate(
		styleData,
		view,
		fields
	);

	return el( DataViews, {
		data: filtered,
		fields,
		view,
		onChangeView: setView,
		actions,
		paginationInfo,
		getItemId: ( item ) => item.slug,
		defaultLayouts: { grid: {} },
	} );
}

domReady( () => {
	const container = document.getElementById( 'artp-style-picker-root' );
	if ( ! container ) {
		return;
	}
	createRoot( container ).render( el( StylePicker, null ) );
} );
