import React, { useState, useEffect } from 'react';
import axe from 'axe-core';

const BulkScan = () => {
    const [posts, setPosts] = useState([]);
    const [selectedPosts, setSelectedPosts] = useState([]);
    const [results, setResults] = useState([]);

    useEffect(() => {
        fetch(`${accessibilityCheckerSettings.restApiUrl}wp/v2/posts?per_page=100`)
            .then((res) => res.json())
            .then((data) => {
                console.log('Fetched posts:', data);
                setPosts(data);
            })
            .catch((error) => console.error('Error fetching posts:', error));
    }, []);

    const togglePostSelection = (postId) => {
        setSelectedPosts((prev) =>
            prev.includes(postId)
                ? prev.filter((id) => id !== postId)
                : [...prev, postId]
        );
    };

    const runBulkScan = async () => {
        const scanResults = [];

        for (const postId of selectedPosts) {
            const post = posts.find((p) => p.ID === postId);
            const htmlContent = post.post_content;

            // Create a container to host the HTML
            const container = document.createElement('div');
            container.innerHTML = htmlContent;

            // Debug: Log container HTML structure
            console.log(`Scanning Post ID: ${postId}`, container.innerHTML);

            // Ensure the container has valid elements
            if (!container.querySelector('*')) {
                console.error(`Post ID: ${postId} has no valid elements for axe-core.`);
                scanResults.push({
                    postTitle: `Post ID: ${postId}`,
                    issues: 'Error: No valid elements for analysis.',
                    details: [],
                });
                continue;
            }

            // Use axe-core for analysis
            try {
                const { violations } = await axe.run(container, {
                    // Include WCAG rules and exclude irrelevant checks
                    rules: {
                        'color-contrast': { enabled: true },
                    },
                });

                scanResults.push({
                    postTitle: `Post ID: ${postId}`, // Use post title if available
                    issues: violations.length,
                    details: violations,
                });
            } catch (error) {
                console.error(`Axe analysis failed for Post ID: ${postId}`, error);
                scanResults.push({
                    postTitle: `Post ID: ${postId}`,
                    issues: 'Error: Axe analysis failed.',
                    details: [],
                });
            }
        }

        setResults(scanResults);
    };

    return (
        <div>
            <h1>Bulk Scan</h1>
            <div>
                {posts.map((post) => (
                    <label key={post.ID}>
                        <input
                            type="checkbox"
                            onChange={() => togglePostSelection(post.ID)}
                            checked={selectedPosts.includes(post.ID)}
                        />
                        {post.post_title}
                    </label>
                ))}
            </div>
            <button onClick={runBulkScan}>Run Scan</button>
            <div>
                {results.map((result, index) => (
                    <div key={index}>
                        <h2>{result.postTitle}</h2>
                        <p>Issues Found: {result.issues}</p>
                        {Array.isArray(result.details) && result.details.length > 0 && (
                            <ul>
                                {result.details.map((issue, idx) => (
                                    <li key={idx}>{issue.description}</li>
                                ))}
                            </ul>
                        )}
                    </div>
                ))}
            </div>
        </div>
    );
};

export default BulkScan;
