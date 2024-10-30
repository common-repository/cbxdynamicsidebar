'use strict';

(function (blocks, element, components, editor, $) {
	var el = element.createElement,
		registerBlockType = blocks.registerBlockType,
		InspectorControls = editor.InspectorControls,
		ServerSideRender = components.ServerSideRender,
		RangeControl = components.RangeControl,
		Panel = components.Panel,
		PanelBody = components.PanelBody,
		PanelRow = components.PanelRow,
		TextControl = components.TextControl,
		//NumberControl = components.NumberControl,
		TextareaControl = components.TextareaControl,
		CheckboxControl = components.CheckboxControl,
		RadioControl = components.RadioControl,
		SelectControl = components.SelectControl,
		ToggleControl = components.ToggleControl,
		//ColorPicker = components.ColorPalette,
		//ColorPicker = components.ColorPicker,
		//ColorPicker = components.ColorIndicator,
		PanelColorPicker = editor.PanelColorSettings,
		DateTimePicker = components.DateTimePicker,
		HorizontalRule = components.HorizontalRule,
		ExternalLink = components.ExternalLink;

	var MediaUpload = wp.editor.MediaUpload;

	/*var iconEl = el('svg', {
		width: 20,
		height: 20
	},
		el('path', {
			d: 'M0 100 l0 -100 100 0 100 0 0 100 0 100 -100 0 -100 0 0 -100z'
		}),
	);*/

	registerBlockType('codeboxr/cbxdynamicsidebar', {
		title: cbxdynamicsidebar_block.block_title,
		icon: 'welcome-widgets-menus',
		category: cbxdynamicsidebar_block.block_category,

		/*
		 * In most other blocks, you'd see an 'attributes' property being defined here.
		 * We've defined attributes in the PHP, that information is automatically sent
		 * to the block editor, so we don't need to redefine it here.
		 */
		edit: function (props) {

			return [
				/*
				 * The ServerSideRender element uses the REST API to automatically call
				 * php_block_render() in your PHP code whenever it needs to get an updated
				 * view of the block.
				 */
				el(ServerSideRender, {
					block: 'codeboxr/cbxdynamicsidebar',
					attributes: props.attributes,
				}),

				el(InspectorControls, {},
					// 1st Panel – Form Settings
					el(PanelBody, {	title: cbxdynamicsidebar_block.general_settings.title,	initialOpen: true},
						el(SelectControl, {
							label: cbxdynamicsidebar_block.general_settings.id,
							options: cbxdynamicsidebar_block.general_settings.id_options,
							onChange: (value) => {
								props.setAttributes({
									id: parseInt(value)
								});
							},
							value: props.attributes.id
						}),
						el(SelectControl, {
							label: cbxdynamicsidebar_block.general_settings.float,
							options: cbxdynamicsidebar_block.general_settings.float_options,
							onChange: (value) => {
								props.setAttributes({
                                    float: value
								});
							},
							value: props.attributes.float
						}),
						el(TextControl, {
							label: cbxdynamicsidebar_block.general_settings.wclass,
							onChange: (value) => {
								props.setAttributes({
                                    wclass: value
								});
							},
							value: props.attributes.wclass
						}),
						el(TextControl, {
							label: cbxdynamicsidebar_block.general_settings.wid,
							onChange: (value) => {
								props.setAttributes({
                                    wid: value
								});
							},
							value: props.attributes.wid
						}),

					)
				)

			]
		},
		// We're going to be rendering in PHP, so save() can just return null.
		save: function () {
			return null;
		},
	});
}(
	window.wp.blocks,
	window.wp.element,
	window.wp.components,
	window.wp.editor,
));