import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar } from '@wordpress/edit-post';
import { PanelBody, TextareaControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import BulkScan from './BulkScan';
import axe from 'axe-core';

const AccessibilityChecker = () => {
    const [results, setResults] = useState([]);
    const enabledRules = accessibilityCheckerSettings.axe_rules
        ? accessibilityCheckerSettings.axe_rules.split(',').map((rule) => rule.trim())
        : null;

    useEffect(() => {
        const checkAccessibility = () => {
            axe.run(
                document.body,
                { rules: enabledRules ? { enabled: enabledRules } : undefined },
                (err, results) => {
                    if (err) {
                        console.error(err);
                        return;
                    }
                    setResults(results.violations);
                }
            );
        };

        checkAccessibility();
    }, [enabledRules]);

    return (
		<PluginSidebar
		name="accessibility-checker"
		title="Accessibility Checker"
		icon="universal-access-alt"
	>
		<PanelBody>
			<h3>Accessibility Issues</h3>
			{results.length ? (
				results.map((violation, index) => (
					<div key={index}>
						<h4>{violation.help}</h4>
						<p>{violation.description}</p>
						<TextareaControl
							value={violation.nodes.map((node) => node.html).join('\n')}
							readOnly
						/>
					</div>
				))
			) : (
				<p>No accessibility issues detected.</p>
			)}

			{/* Render BulkScan component here */}
			<BulkScan />
		</PanelBody>
	</PluginSidebar>

    );
};

registerPlugin('accessibility-checker', {
    render: AccessibilityChecker,
});
