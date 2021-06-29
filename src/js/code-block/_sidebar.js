/**
 * @WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, RadioControl } from "@wordpress/components";
import { memo } from "@wordpress/element";

export default memo(({ attributes, setAttributes }) => {
	const { isLineShow, isShowLang } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={__("HCB settings", "ayoub-bccb")}
				initialOpen={true}
			>
				<RadioControl
					label={__(
						"Settings for displaying the number of lines",
						"ayoub-bccb"
					)} // 行数の表示に関する設定
					selected={isLineShow}
					options={[
						{
							label: __("Do not set individually", "ayoub-bccb"), //個別で設定はしない
							value: "undefined",
						},
						{
							label: __("Display row count", "ayoub-bccb"), //行数を表示する
							value: "on",
						},
						{
							label: __("Do not display row count", "ayoub-bccb"), //行数を表示しない
							value: "off",
						},
					]}
					onChange={(val) => {
						setAttributes({ isLineShow: val });
					}}
				/>

				<RadioControl
					label={__(
						"Settings for displaying language name",
						"ayoub-bccb"
					)} // 言語名の表示に関する設定
					selected={isShowLang}
					options={[
						{
							label: __("Do not set individually", "ayoub-bccb"), //個別で設定はしない
							value: "",
						},
						{
							label: __("Display language", "ayoub-bccb"), //言語を表示する
							value: "1",
						},
						{
							label: __("Do not display language", "ayoub-bccb"), //言語を表示しない
							value: "0",
						},
					]}
					onChange={(val) => {
						setAttributes({ isShowLang: val });
					}}
				/>
			</PanelBody>
		</InspectorControls>
	);
});
