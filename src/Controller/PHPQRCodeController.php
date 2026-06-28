<?php

declare(strict_types=1);

/*
 * This file is part of the PHP QR Code bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PHPQRCodeBundle\Controller;

use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode\PHPQRCodeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/qr')]
class PHPQRCodeController extends AbstractController
{
    public function __construct(
        private readonly PHPQRCodeInterface $qr,
        private readonly string $defaultLevel,
        private readonly int $defaultSize,
        private readonly int $defaultMargin,
        private readonly int $maxTextLength,
        private readonly ?string $accessRole = null,
    ) {
    }

    #[Route('/png/{level}/{size}/{margin}', name: 'qrcode_png', requirements: ['level' => 'L|M|Q|H', 'size' => '\d+', 'margin' => '\d+'])]
    public function getQRCodePNGAction(Request $request, string $level, int $size, int $margin): Response
    {
        $this->denyUnlessAllowed();

        $text = $this->resolveText($request);
        $this->assertValidParams($size, $margin);

        return $this->respond($this->qr->generatePNG($text, $level, $size, $margin), $request);
    }

    #[Route('/png', name: 'qrcode_png_default')]
    public function getQRCodePNGwDefaultsAction(Request $request): Response
    {
        $this->denyUnlessAllowed();

        $text = $this->resolveText($request);

        return $this->respond(
            $this->qr->generatePNG($text, $this->defaultLevel, $this->defaultSize, $this->defaultMargin),
            $request
        );
    }

    #[Route('/svg/{level}/{size}/{margin}', name: 'qrcode_svg', requirements: ['level' => 'L|M|Q|H', 'size' => '\d+', 'margin' => '\d+'])]
    public function getQRCodeSVGAction(Request $request, string $level, int $size, int $margin): Response
    {
        $this->denyUnlessAllowed();

        $text = $this->resolveText($request);
        $this->assertValidParams($size, $margin);

        return $this->respond($this->qr->generateSVG($text, $level, $size, $margin), $request);
    }

    #[Route('/svg', name: 'qrcode_svg_default')]
    public function getQRCodeSVGwDefaultsAction(Request $request): Response
    {
        $this->denyUnlessAllowed();

        $text = $this->resolveText($request);

        return $this->respond(
            $this->qr->generateSVG($text, $this->defaultLevel, $this->defaultSize, $this->defaultMargin),
            $request
        );
    }

    /**
     * Enforce the configured access role, if any.
     */
    private function denyUnlessAllowed(): void
    {
        if (null !== $this->accessRole) {
            $this->denyAccessUnlessGranted($this->accessRole);
        }
    }

    /**
     * Read and validate the ?text= query parameter (DoS-bounded).
     */
    private function resolveText(Request $request): string
    {
        $text = $request->query->getString('text');

        if (\strlen($text) > $this->maxTextLength) {
            throw new BadRequestHttpException(sprintf('Parameter "text" exceeds the maximum length of %d bytes.', $this->maxTextLength));
        }

        $text = trim($text);

        return '' === $text ? 'EMPTY' : $text;
    }

    /**
     * Clamp/whitelist the numeric route parameters.
     */
    private function assertValidParams(int $size, int $margin): void
    {
        if ($size < 1 || $size > 10) {
            throw new BadRequestHttpException('Parameter "size" must be between 1 and 10.');
        }

        if ($margin < 0 || $margin > 100) {
            throw new BadRequestHttpException('Parameter "margin" must be between 0 and 100.');
        }
    }

    /**
     * Honour conditional requests (304) for the cacheable QR response.
     */
    private function respond(Response $response, Request $request): Response
    {
        $response->isNotModified($request);

        return $response;
    }
}
