<?php

/**
 * ViewHelper for repeating elements
 *
 * examples:
 *
 * <ce:repeat count="3">
 * 		<p>Hello is printed 3 times</p>
 * </ce:repeat>
 *
 * <ce:repeat count="3" iteration="index">
 * 		<p>Iteration number: {index}</p>
 * </ce:repeat>
 */
class Tx_Cicevents_ViewHelpers_RepeatViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Iterates through elements
	 *
	 * @param integer $count The number of times to repeat the child elements.
	 * @param string $iteration The name of the variable for storing the iteration.
	 * @return string Rendered string
	 */
	public function render($count = 0, $iteration = '') {
		return self::renderStatic($this->arguments, $this->buildRenderChildrenClosure(), $this->renderingContext);
	}

	/**
	 * @param array $arguments
	 * @param Closure $renderChildrenClosure
	 * @param Tx_Fluid_Core_Rendering_RenderingContextInterface $renderingContext
	 * @return string
	 */
	static public function renderStatic(array $arguments, Closure $renderChildrenClosure, Tx_Fluid_Core_Rendering_RenderingContextInterface $renderingContext) {
		$templateVariableContainer = $renderingContext->getTemplateVariableContainer();

		$count = $arguments['count'];
		$output = "";
		for($i = 0; $i < $count; ++$i) {
			if ($arguments['as'] !== '') {
				$templateVariableContainer->add($arguments['iteration'], $i + 1);
			}
			$output .= $renderChildrenClosure();
			if ($arguments['as'] !== '') {
				$templateVariableContainer->remove($arguments['iteration']);
			}
		}
		return $output;
	}
}
?>
