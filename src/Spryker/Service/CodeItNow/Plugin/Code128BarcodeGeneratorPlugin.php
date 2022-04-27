<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CodeItNow\Plugin;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Laminas\Barcode\Barcode;
use Laminas\Barcode\Renderer\RendererInterface;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Service\BarcodeLaminas\Plugin\Code128BarcodeGeneratorPlugin} instead.
 */
class Code128BarcodeGeneratorPlugin extends AbstractPlugin implements BarcodeGeneratorPluginInterface
{
    /**
     * @var string
     */
    public const ENCODING = 'data:image/png;base64';

    /**
     * @var string
     */
    protected const BARCODE_TYPE = 'code128';

    /**
     * @var string
     */
    protected const RENDERER_IMAGE = 'image';

    /**
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer
    {
        $renderer = $this->createRenderer($text);

        ob_start();
        $renderer->render();

        /** @var string $contents */
        $contents = ob_get_clean();

        return $this->createBarcodeResponseTransfer(base64_encode($contents));
    }

    /**
     * @param string $text
     *
     * @return \Laminas\Barcode\Renderer\RendererInterface
     */
    protected function createRenderer(string $text): RendererInterface
    {
        $barcodeOptions = ['text' => $text];

        return Barcode::factory(
            static::BARCODE_TYPE,
            static::RENDERER_IMAGE,
            $barcodeOptions,
        );
    }

    /**
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    protected function createBarcodeResponseTransfer(string $text): BarcodeResponseTransfer
    {
        return (new BarcodeResponseTransfer())
            ->setCode($text)
            ->setEncoding(static::ENCODING);
    }
}
