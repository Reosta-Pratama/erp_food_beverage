(function () {

    "use strict";

    /* === Default === */
    const defaultSlider = document.getElementById('slider');

    if (defaultSlider) {
        noUiSlider.create(defaultSlider, {
            start: [
                30, 80
            ],
            connect: true,
            range: {
                'min': 0,
                'max': 100
            }
        });
    }
    /* === Default === */

    /* === Handle Slider === */
    const handleSlider = document.getElementById('slider-fit');

    if (handleSlider) {
        noUiSlider.create(handleSlider, {
            start: [
                50, 90
            ],
            connect: true,
            range: {
                'min': 1,
                'max': 100
            }
        });
    }
    /* === Handle Slider === */

    /* === Rounded === */
    const roundedSlider = document.getElementById('slider-round');

    if (roundedSlider) {
        noUiSlider.create(roundedSlider, {
            start: [50],
            step: 1,
            connect: [
                true, false
            ],
            range: {
                'min': 0,
                'max': 100
            }
        });
    }
    /* === Rounded === */

    /* === Squared === */
    const sliderSquare = document.getElementById('slider-square');

    if (sliderSquare) {
        noUiSlider.create(sliderSquare, {
            start: [40],
            connect: true,
            range: {
                'min': 0,
                'max': 100
            }
        });
    }
    /* === Squared === */

    /* === Primary Color === */
    const primaryColoredSlider = document.getElementById('primary-colored-slider');

    if (primaryColoredSlider) {
        noUiSlider.create(primaryColoredSlider, {
            start: [50],
            step: 1,
            connect: [
                true, false
            ],
            range: {
                min: 0,
                max: 100
            }
        });
    }
    /* === Primary Color === */

    /* === Secondary Color === */
    const secondaryColoredSlider = document.getElementById(
        'secondary-colored-slider'
    );

    if (secondaryColoredSlider) {
        noUiSlider.create(secondaryColoredSlider, {
            start: [50],
            step: 1,
            connect: [
                true, false
            ],
            range: {
                min: 0,
                max: 100
            }
        });
    }
    /* === Secondary Color === */

    /* === Warning Color === */
    const warningColoredSlider = document.getElementById('warning-colored-slider');

    if (warningColoredSlider) {
        noUiSlider.create(warningColoredSlider, {
            start: [50],
            step: 1,
            connect: [
                true, false
            ],
            range: {
                min: 0,
                max: 100
            }
        });
    }
    /* === Warning Color === */

    /* === Info Color === */
    const infoColoredSlider = document.getElementById('info-colored-slider');

    if (infoColoredSlider) {
        noUiSlider.create(infoColoredSlider, {
            start: [50],
            step: 1,
            connect: [
                true, false
            ],
            range: {
                min: 0,
                max: 100
            }
        });
    }
    /* === Info Color === */

    /* === Success Color === */
    const successColoredSlider = document.getElementById('success-colored-slider');

    if (successColoredSlider) {
        noUiSlider.create(successColoredSlider, {
            start: [50],
            step: 1,
            connect: [
                true, false
            ],
            range: {
                min: 0,
                max: 100
            }
        });
    }
    /* === Success Color === */

    /* === Danger Color === */
    const dangerColoredSlider = document.getElementById('danger-colored-slider');

    if (dangerColoredSlider) {
        noUiSlider.create(dangerColoredSlider, {
            start: [50],
            step: 1,
            connect: [
                true, false
            ],
            range: {
                min: 0,
                max: 100
            }
        });
    }
    /* === Danger Color === */

    /* === Color Picker === */
    const resultElement = document.getElementById('result');
    const sliders = document.querySelectorAll('.sliders');
    const colors = [0, 0, 0];

    if (resultElement && sliders.length > 0) {
        sliders.forEach((slider, index) => {
            noUiSlider.create(slider, {
                start: 127,
                connect: [
                    true, false
                ],
                orientation: 'vertical',
                range: {
                    min: 0,
                    max: 255
                },
                format: wNumb({decimals: 0})
            });

            // Bind color changing function to the update event
            slider
                .noUiSlider
                .on('update', () => {
                    colors[index] = parseInt(slider.noUiSlider.get());
                    const color = `rgb(${colors.join(',')})`;
                    resultElement.style.background = color;
                    resultElement.style.color = color;
                });
        });
    }
    /* === Color Picker === */

    /* === Locking Slider === */
    let lockedState = false;
    let lockedValues = [60, 80];

    const slider1 = document.getElementById('slider1');
    const slider2 = document.getElementById('slider2');

    const lockButton = document.getElementById('lockbutton');
    const slider1Value = document.getElementById('slider1-span');
    const slider2Value = document.getElementById('slider2-span');

    // Toggle lock state when button is clicked
    if (lockButton) {
        lockButton.addEventListener('click', function () {
            lockedState = !lockedState;
            this.textContent = lockedState
                ? 'unlock'
                : 'lock';
            setLockedValues(); // Refresh locked values on toggle
        });
    }

    // Cross-update function to keep distance when locked
    function crossUpdate(value, sourceSlider) {
        if (!lockedState) 
            return;
        
        const isSlider1 = slider1 === sourceSlider;
        const a = isSlider1
            ? 0
            : 1;
        const b = isSlider1
            ? 1
            : 0;

        const newValue = value - (lockedValues[b] - lockedValues[a]);

        const targetSlider = isSlider1
            ? slider2
            : slider1;
        targetSlider
            .noUiSlider
            .set(newValue);
    }

    // Initialize slider1
    if (slider1) {
        noUiSlider.create(slider1, {
            start: 60,
            animate: false,
            range: {
                min: 50,
                max: 100
            }
        });

        slider1
            .noUiSlider
            .on('update', function (values, handle) {
                if (slider1Value) {
                    slider1Value.textContent = values[handle];
                }
            });

        slider1
            .noUiSlider
            .on('change', setLockedValues);
        slider1
            .noUiSlider
            .on('slide', function (values, handle) {
                crossUpdate(Number(values[handle]), slider1);
            });
    }

    // Initialize slider2
    if (slider2) {
        noUiSlider.create(slider2, {
            start: 80,
            animate: false,
            range: {
                min: 50,
                max: 100
            }
        });

        slider2
            .noUiSlider
            .on('update', function (values, handle) {
                if (slider2Value) {
                    slider2Value.textContent = values[handle];
                }
            });

        slider2
            .noUiSlider
            .on('change', setLockedValues);
        slider2
            .noUiSlider
            .on('slide', function (values, handle) {
                crossUpdate(Number(values[handle]), slider2);
            });
    }

    // Update locked values for both sliders
    function setLockedValues() {
        if (slider1 && slider2) {
            lockedValues = [
                Number(slider1.noUiSlider.get()),
                Number(slider2.noUiSlider.get())
            ];
        }
    }
    /* === Locking Slider === */

    /* === Merge === */
    const mergingTooltipSlider = document.getElementById('merging-tooltips');

    if (mergingTooltipSlider) {
        noUiSlider.create(mergingTooltipSlider, {
            start: [
                20, 80
            ],
            connect: true,
            tooltips: [
                true, true
            ],
            range: {
                min: 0,
                max: 100
            }
        });

        mergeTooltips(mergingTooltipSlider, 15, ' - ');
    }

    function mergeTooltips(slider, threshold, separator) {
        const style = getComputedStyle(slider);
        const textIsRtl = style.direction === 'rtl';
        const {direction, orientation} = slider.noUiSlider.options;
        const isRtl = direction === 'rtl';
        const isVertical = orientation === 'vertical';

        const tooltips = slider
            .noUiSlider
            .getTooltips();
        const origins = slider
            .noUiSlider
            .getOrigins();

        // Move tooltips into origin elements
        tooltips.forEach((tooltip, index) => {
            if (tooltip) {
                origins[index].appendChild(tooltip);
            }
        });

        slider
            .noUiSlider
            .on('update', (values, handle, unencoded, tap, positions) => {
                const pools = [
                    []
                ];
                const poolPositions = [
                    []
                ];
                const poolValues = [
                    []
                ];
                let currentPool = 0;

                // Initialize first tooltip
                if (tooltips[0]) {
                    pools[0][0] = 0;
                    poolPositions[0][0] = positions[0];
                    poolValues[0][0] = values[0];
                }

                for (let i = 1; i < positions.length; i++) {
                    const distance = positions[i] - positions[i - 1];
                    const shouldCreateNewPool = !tooltips[i] || distance > threshold;

                    if (shouldCreateNewPool) {
                        currentPool++;
                        pools[currentPool] = [];
                        poolPositions[currentPool] = [];
                        poolValues[currentPool] = [];
                    }

                    if (tooltips[i]) {
                        pools[currentPool].push(i);
                        poolPositions[currentPool].push(positions[i]);
                        poolValues[currentPool].push(values[i]);
                    }
                }

                // Render merged tooltips
                pools.forEach((pool, poolIndex) => {
                    const handlesInPool = pool.length;

                    for (let j = 0; j < handlesInPool; j++) {
                        const handleNumber = pool[j];

                        if (j === handlesInPool - 1) {
                            // Compute tooltip offset
                            let offset = 0;
                            poolPositions[poolIndex].forEach(pos => {
                                offset += 1000 - pos;
                            });

                            const directionStyle = isVertical
                                ? 'bottom'
                                : 'right';
                            const lastIndex = isRtl
                                ? 0
                                : handlesInPool - 1;
                            const lastOffset = 1000 - poolPositions[poolIndex][lastIndex];

                            offset = (
                                textIsRtl && !isVertical
                                    ? 100
                                    : 0
                            ) + (offset / handlesInPool) - lastOffset;

                            // Show merged tooltip
                            tooltips[handleNumber].innerHTML = poolValues[poolIndex].join(separator);
                            tooltips[handleNumber].style.display = 'block';
                            tooltips[handleNumber].style[directionStyle] = `${offset}%`;
                        } else {
                            // Hide this tooltip
                            tooltips[handleNumber].style.display = 'none';
                        }
                    }
                });
            });
    }
    /* === Merge === */

    /* === Nonlinear === */
    const nonLinearSlider = document.getElementById('nonlinear');

    if (nonLinearSlider) {
        noUiSlider.create(nonLinearSlider, {
            connect: true,
            behaviour: 'tap',
            start: [
                500, 4000
            ],
            range: {
                min: [0],
                '10%': [
                    500, 500
                ],
                '50%': [
                    4000, 1000
                ],
                max: [10000]
            }
        });

        const nodes = [
            document.getElementById('lower-value'), // Handle 0
            document.getElementById('upper-value') // Handle 1
        ];

        nonLinearSlider
            .noUiSlider
            .on('update', function (values, handle, unencoded, isTap, positions) {
                if (nodes[handle]) {
                    nodes[handle].textContent = `${values[handle]}, ${positions[handle].toFixed(2)}%`;
                }
            });
    }
    /* === Nonlinear === */

    /* === Handle Tooltip === */
    const tooltipSlider = document.getElementById('slider-hide');

    if (tooltipSlider) {
        noUiSlider.create(tooltipSlider, {
            start: [
                30, 80
            ],
            tooltips: [
                true, true
            ],
            connect: true,
            range: {
                min: 0,
                max: 100
            }
        });
    }
    /* === Handle Tooltip === */

    /* === Color Connect Element === */
    const colorSlider = document.getElementById("color-slider");

    if (colorSlider) {
        noUiSlider.create(colorSlider, {
            start: [
                20, 150, 220, 270
            ],
            connect: [
                false, true, true, true, true
            ],
            range: {
                min: 0,
                max: 300
            }
        });

        const connectElements = colorSlider.querySelectorAll('.noUi-connect');
        const colorClasses = ['c-1-color', 'c-2-color', 'c-3-color', 'c-4-color', 'c-5-color'];

        connectElements.forEach((el, index) => {
            if (colorClasses[index]) {
                el
                    .classList
                    .add(colorClasses[index]);
            }
        });
    }
    /* === Color Connect Element === */

    /* === Slider Toggle === */
    const toggleSlider = document.getElementById('slider-toggle');

    if (toggleSlider) {
        noUiSlider.create(toggleSlider, {
            orientation: "vertical",
            start: 0,
            range: {
                min: [
                    0, 1
                ],
                max: 1
            },
            format: wNumb({decimals: 0})
        });

        toggleSlider
            .noUiSlider
            .on('update', (values, handle) => {
                const isOff = values[handle] === '1';
                toggleSlider
                    .classList
                    .toggle('off', isOff);
            });
    }
    /* === Slider Toggle === */

    /* === Moving Slider With Clicking Pips === */
    const pipsSlider = document.getElementById('slider-pips');

    if (pipsSlider) {
        noUiSlider.create(pipsSlider, {
            range: {
                min: 0,
                max: 100
            },
            start: [50],
            pips: {
                mode: 'count',
                values: 5
            }
        });

        const pips = pipsSlider.querySelectorAll('.noUi-value');

        function clickOnPip() {
            const value = Number(this.getAttribute('data-value'));
            pipsSlider
                .noUiSlider
                .set(value);
        }

        pips.forEach(pip => {
            pip.style.cursor = 'pointer'; // (ideally done via CSS)
            pip.addEventListener('click', clickOnPip);
        });
    }
    /* === Moving Slider With Clicking Pips === */

    /* === Soft Limit === */
    const softSlider = document.getElementById('soft');

    if (softSlider) {
        noUiSlider.create(softSlider, {
            start: 50,
            range: {
                min: 0,
                max: 100
            },
            pips: {
                mode: 'values',
                values: [
                    20, 80
                ],
                density: 4
            }
        });

        softSlider
            .noUiSlider
            .on('change', (values, handle) => {
                const value = Number(values[handle]);

                if (value < 20) {
                    softSlider
                        .noUiSlider
                        .set(20);
                } else if (value > 80) {
                    softSlider
                        .noUiSlider
                        .set(80);
                }
            });
    }
    /* === Soft Limit === */

})();
