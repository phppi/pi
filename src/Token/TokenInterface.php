<?php declare(strict_types = 1);

namespace PiCompiler\Token;

interface TokenInterface
{
	public function getParent(): ?TokenInterface;
	public function setParent(?TokenInterface $token);
	
//	/** @return \PiCompiler\Token\TokenInterface[] */
//	public function getChildren(): array;
}
