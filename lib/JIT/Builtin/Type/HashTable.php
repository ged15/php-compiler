<?php

// This file is generated and changes you make will be lost.
// Change /Users/ged15/Projects/php-compiler/lib/JIT/Builtin/Type/HashTable.pre instead.

// This file is generated and changes you make will be lost.
// Change /compiler/lib/JIT/Builtin/Type/HashTable.pre instead.

/*
 * This file is part of PHP-Compiler, a PHP CFG Compiler for PHP code
 *
 * @copyright 2015 Anthony Ferrara. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */

namespace PHPCompiler\JIT\Builtin\Type;

use PHPCompiler\JIT\Builtin\Type;
use PHPCompiler\JIT\Builtin\Refcount;
use PHPCompiler\JIT\Variable;
use function \PHPCompiler\debug;
use PHPLLVM;

class HashTable extends Type {
    public PHPLLVM\Type $pointer;

    public function register(): void {
        

        

        $struct___cfcd208495d565ef66e7dff9f98764da = $this->context->context->namedStructType('__htbucket__');
            // declare first so recursive structs are possible :)
            $this->context->registerType('__htbucket__', $struct___cfcd208495d565ef66e7dff9f98764da);
            $this->context->registerType('__htbucket__' . '*', $struct___cfcd208495d565ef66e7dff9f98764da->pointerType(0));
            $this->context->registerType('__htbucket__' . '**', $struct___cfcd208495d565ef66e7dff9f98764da->pointerType(0)->pointerType(0));
            $struct___cfcd208495d565ef66e7dff9f98764da->setBody(
                false ,  // packed
                $this->context->getTypeFromString('size_t')
                , $this->context->getTypeFromString('size_t')
                , $this->context->getTypeFromString('size_t')
                
            );
            $this->context->structFieldMap['__htbucket__'] = [
                'hash' => 0
                , 'key' => 1
                , 'value' => 2
                
            ];
        
    

        

        $struct___cfcd208495d565ef66e7dff9f98764da = $this->context->context->namedStructType('__hashtable__');
            // declare first so recursive structs are possible :)
            $this->context->registerType('__hashtable__', $struct___cfcd208495d565ef66e7dff9f98764da);
            $this->context->registerType('__hashtable__' . '*', $struct___cfcd208495d565ef66e7dff9f98764da->pointerType(0));
            $this->context->registerType('__hashtable__' . '**', $struct___cfcd208495d565ef66e7dff9f98764da->pointerType(0)->pointerType(0));
            $struct___cfcd208495d565ef66e7dff9f98764da->setBody(
                false ,  // packed
                $this->context->getTypeFromString('__ref__')
                , $this->context->getTypeFromString('__htbucket__**')
                
            );
            $this->context->structFieldMap['__hashtable__'] = [
                'ref' => 0
                , 'buckets' => 1
                
            ];
        
    $fntype___cfcd208495d565ef66e7dff9f98764da = $this->context->context->functionType(
                $this->context->getTypeFromString('void'),
                false , 
                $this->context->getTypeFromString('__hashtable__*')
                , $this->context->getTypeFromString('size_t')
                , $this->context->getTypeFromString('size_t')
                
            );
            $fn___cfcd208495d565ef66e7dff9f98764da = $this->context->module->addFunction('__hashtable__insert', $fntype___cfcd208495d565ef66e7dff9f98764da);
            
            
            
            
            
            $this->context->registerFunction('__hashtable__insert', $fn___cfcd208495d565ef66e7dff9f98764da);
        

        

        
    $fntype___cfcd208495d565ef66e7dff9f98764da = $this->context->context->functionType(
                $this->context->getTypeFromString('__htbucket__*'),
                false , 
                $this->context->getTypeFromString('size_t')
                
            );
            $fn___cfcd208495d565ef66e7dff9f98764da = $this->context->module->addFunction('__hashtable__search', $fntype___cfcd208495d565ef66e7dff9f98764da);
            
            
            
            $this->context->registerFunction('__hashtable__search', $fn___cfcd208495d565ef66e7dff9f98764da);
        

        

        
    
    }

    public function implement(): void
    {
        $this->implementInsert();
        $this->implementSearch();
    }

    private function implementInsert()
    {
        $fn___c4ca4238a0b923820dcc509a6f75849b = $this->context->lookupFunction('__hashtable__insert');
    $block___c4ca4238a0b923820dcc509a6f75849b = $fn___c4ca4238a0b923820dcc509a6f75849b->appendBasicBlock('main');
    $this->context->builder->positionAtEnd($block___c4ca4238a0b923820dcc509a6f75849b);
    $hashtable = $fn___c4ca4238a0b923820dcc509a6f75849b->getParam(0);
    $index = $fn___c4ca4238a0b923820dcc509a6f75849b->getParam(1);
    $value = $fn___c4ca4238a0b923820dcc509a6f75849b->getParam(2);
    
    $offset = $this->context->structFieldMap[$hashtable->typeOf()->getElementType()->getName()]['buckets'];
                    $htbuckets = $this->context->builder->structGep($hashtable, $offset);
    $type = $this->context->getTypeFromString('__htbucket__');
                    $struct = $this->context->memory->mallocWithExtra($type, $index);
    $offset = $this->context->structFieldMap[$struct->typeOf()->getElementType()->getName()]['key'];
                    $keyPtr = $this->context->builder->structGep($struct, $offset);
    $this->context->builder->store($index, $keyPtr);
    $offset = $this->context->structFieldMap[$struct->typeOf()->getElementType()->getName()]['value'];
                    $valuePtr = $this->context->builder->structGep($struct, $offset);
    $this->context->builder->store($value, $valuePtr);
    $type = $this->context->getTypeFromString('__htbucket__');
                    $currentItem = $this->context->builder->alloca($type);
    $currentItemPtr = $this->context->builder->load($this->context->builder->gep(
                        $htbuckets,
                        //$this->context->context->int32Type()->constInt(0, false),
                        //$this->context->context->int32Type()->constInt(0, false),
                        $index
                    ));
    $this->context->builder->store($currentItemPtr, $currentItem);
    $type = $this->context->getTypeFromString('size_t');
                    $currentItemIndexPtr = $this->context->builder->alloca($type);
    $this->context->builder->store($currentItemIndexPtr, $index);
    $prev = $this->context->builder->getInsertBlock();

                $loopBlock = $prev->insertBasicBlock('loopBlock');
                $prev->moveBefore($loopBlock);

                $iterateBlock = $prev->insertBasicBlock('iterateBlock');
                $prev->moveBefore($iterateBlock);

                $afterLoopBlock = $iterateBlock->insertBasicBlock('afterLoopBlock');
                $prev->moveBefore($afterLoopBlock);

                $this->context->builder->branch($loopBlock);

                $this->context->builder->positionAtEnd($loopBlock);
                $bool = $this->context->builder->icmp(\PHPLLVM\Builder::INT_EQ, $currentItem, $currentItem->typeOf()->constNull());
    
                $this->context->builder->branchIf($bool, $iterateBlock, $afterLoopBlock);

                $this->context->builder->positionAtEnd($iterateBlock);
                $currentItemIndex = $this->context->builder->load($currentItemIndexPtr);
    $currentItemIndex = $this->context->builder->add($currentItemIndex, $currentItemIndex->typeOf()->constInt(1, false));
    $__right = $currentItemIndex->typeOf()->constInt(20, false);
                            
                        

                        

                        

                        

                        

                        

                        

                        

                        
                            $currentItemIndex = $this->context->builder->signedRem($currentItemIndex, $__right);
    $currentItemPtr = $this->context->builder->load($this->context->builder->gep(
                        $htbuckets,
                        //$this->context->context->int32Type()->constInt(0, false),
                        //$this->context->context->int32Type()->constInt(0, false),
                        $index
                    ));
    $this->context->builder->store($currentItemPtr, $currentItem);
    $this->context->builder->store($currentItemIndexPtr, $currentItemIndex);
    
                $this->context->builder->branch($loopBlock);

                $this->context->builder->positionAtEnd($afterLoopBlock);
    $currentItemIndex = $this->context->builder->load($currentItemIndexPtr);
    $currentItemPtr = $this->context->builder->load($this->context->builder->gep(
                        $htbuckets,
                        //$this->context->context->int32Type()->constInt(0, false),
                        //$this->context->context->int32Type()->constInt(0, false),
                        $currentItemIndex
                    ));
    $this->context->builder->store($currentItemPtr, $struct);
    $this->context->builder->returnVoid();
    
    $this->context->builder->clearInsertionPosition();

        /*
        $context = $this->context;
        $builder = $context->builder;

        $function = $this->context->lookupFunction('__hashtable__insert');

        $mainBlock = $function->appendBasicBlock('main');
        $this->context->builder->positionAtEnd($mainBlock);

        $prev = $this->context->builder->getInsertBlock();

        $loopBlock = $prev->insertBasicBlock('loop');
        $prev->moveBefore($loopBlock);

        $iterateBlock = $prev->insertBasicBlock('iterate');
        $prev->moveBefore($iterateBlock);

        $insertBlock = $prev->insertBasicBlock('insert');
        $prev->moveBefore($insertBlock);

        $hashtable = $function->getParam(0);
        $index = $function->getParam(1);
        $value = $function->getParam(2);

        $htbuckets = $this->context->builder->structGep(
            $hashtable,
            $context->structFieldMap[$hashtable->typeOf()->getElementType()->getName()]['buckets'],
        );

        $htbucketType = $this->context->getTypeFromString('__htbucket__');
        $struct = $this->context->memory->mallocWithExtra($htbucketType, $index);
        $this->context->builder->store(
            $index,
            $this->context->builder->structGep(
                $struct,
                $this->context->structFieldMap[$struct->typeOf()->getElementType()->getName()]['key']
            )
        );
        $this->context->builder->store(
            $value,
            $this->context->builder->structGep(
                $struct,
                $this->context->structFieldMap[$struct->typeOf()->getElementType()->getName()]['value']
            )
        );

        $sizeTType = $this->context->getTypeFromString('size_t');

        $elementsPointer = $builder->alloca($htbuckets->typeOf());
        $indexPointer = $builder->alloca($sizeTType);
        $builder->store($index, $indexPointer);

        $builder->positionAtEnd($loopBlock);
        $index = $builder->load($indexPointer);
        $elementPointer = $builder->inBoundsGep($elementsPointer, $sizeTType->constInt(0, false), $index);
        $element = $builder->load($elementPointer);
        $comparisonResult = $builder->iCmp(\PHPLLVM\Builder::INT_EQ, $element, $element->typeOf()->constNull());
        $builder->branchIf($comparisonResult, $insertBlock, $iterateBlock);

        $builder->positionAtEnd($iterateBlock);
        $index = $builder->load($indexPointer);
        $incrementedIndex = $builder->add($index, $sizeTType->constInt(1, false));
        $wrappedIndex = $builder->unsigendRem($incrementedIndex, $sizeTType->constInt(3, false)); // todo use actual hashtable size
        $builder->store($wrappedIndex, $indexPointer);
        $builder->branch($loopBlock);

        $builder->positionAtEnd($insertBlock);
        $index = $builder->load($indexPointer);
        $builder->store($struct, $builder->inBoundsGep(
            $htbuckets,
            $context->getTypeFromString('size_t')->constInt(0, false),
            $index,
        ));




//        while(hashArray[hashIndex] != NULL) {
//            //go to next cell
//            ++hashIndex;
//
//            //wrap around the table
//            hashIndex %= SIZE;
//        }
//
//        hashArray[hashIndex] = item;

////                $hashIndexValue = $builder->load($hashIndexPointer);
//        $builder->store($struct, $builder->inBoundsGep(
//            $htbuckets,
//            $context->getTypeFromString('size_t')->constInt(0, false),
//            $hashIndexValue,
//        ));
//                $builder->store(
//                    $builder->add($hashIndexValue, $context->getTypeFromString('size_t')->constInt(1, false)),
//                    $hashIndexPointer
//                );

        $this->context->builder->clearInsertionPosition();
        */
    }

    private function implementSearch()
    {
        $builder = $this->context->builder;

        $hashtableSearchFn = $this->context->lookupFunction('__hashtable__search');

        $mainBlock = $hashtableSearchFn->appendBasicBlock('main');
        $builder->positionAtEnd($mainBlock);

//        $hashTable = $hashtableSearchFn->getParam(0);
        $key = $hashtableSearchFn->getParam(0);
        // todo calculate key

        $prev = $this->context->builder->getInsertBlock();

        $loopBlock = $prev->insertBasicBlock('loop');
        $prev->moveBefore($loopBlock);

        $returnNullBlock = $prev->insertBasicBlock('returnNull');
        $prev->moveBefore($returnNullBlock);

        $iterateBlock = $prev->insertBasicBlock('iterate');
        $prev->moveBefore($iterateBlock);

        $returnElementBlock = $prev->insertBasicBlock('returnElement');
        $prev->moveBefore($returnElementBlock);

        $nextCellBlock = $prev->insertBasicBlock('nextCell');
        $prev->moveBefore($nextCellBlock);

        $returnBlock = $prev->insertBasicBlock('return');
        $prev->moveBefore($returnBlock);

        $sizeTType = $this->context->getTypeFromString('size_t');

        $type = $this->context->getTypeFromString('__htbucket__*[3]');
        $elementsPointer = $builder->alloca($type);
        // todo store into $elements
        $indexPointer = $builder->alloca($sizeTType);
        $builder->store($sizeTType->constInt(0, false), $indexPointer);
        $elementToReturnPointer = $builder->alloca(
            $this->context->getTypeFromString('__htbucket__*')
        );
        $builder->branch($loopBlock);

        $builder->positionAtEnd($loopBlock);
        $index = $builder->load($indexPointer);
        $elementPointer = $builder->inBoundsGep($elementsPointer, $sizeTType->constInt(0, false), $index);
        $element = $builder->load($elementPointer);
        $comparisonResult = $builder->iCmp(\PHPLLVM\Builder::INT_EQ, $element, $element->typeOf()->constNull());
        $builder->branchIf($comparisonResult, $returnNullBlock, $iterateBlock);

        $builder->positionAtEnd($iterateBlock);
        $index = $builder->load($indexPointer);
        $currentElementPointer = $builder->inBoundsGep($elementsPointer, $sizeTType->constInt(0, false), $index);
        $currentElement = $builder->load($currentElementPointer);
        $elementKey = $builder->load($builder->structGep($currentElement, 1));
        $comparisonResult = $builder->iCmp(\PHPLLVM\Builder::INT_EQ, $elementKey, $key);
        $builder->branchIf($comparisonResult, $returnElementBlock, $nextCellBlock);

        $builder->positionAtEnd($returnElementBlock);
        $index = $builder->load($indexPointer);
        $currentElementPointer = $builder->inBoundsGep($elementsPointer, $sizeTType->constInt(0, false), $index);
        $currentElement = $builder->load($currentElementPointer);
        $builder->store($currentElement, $elementToReturnPointer);
        $builder->branch($returnBlock);

        $builder->positionAtEnd($returnNullBlock);
        $builder->store($this->context->getTypeFromString('__htbucket__*')->constNull(), $elementToReturnPointer);
        $builder->branch($returnBlock);

        $builder->positionAtEnd($returnBlock);
        $elementToReturn = $builder->load($elementToReturnPointer);
        $builder->returnValue($elementToReturn);

        $builder->positionAtEnd($nextCellBlock);
        $index = $builder->load($indexPointer);
        $incrementedIndex = $builder->add($index, $sizeTType->constInt(1, false));
        $wrappedIndex = $builder->unsigendRem($incrementedIndex, $sizeTType->constInt(3, false));
        $builder->store($wrappedIndex, $indexPointer);
        $builder->branch($loopBlock);

        $builder->clearInsertionPosition();
    }

    public function initialize(): void {
    }
}