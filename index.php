<?php

/**
 * @param array $node
 * @param array $pool
 */
function processTree(array &$node, array &$pool = []) {
  $node['values'] = array_merge($node['values'], $pool);
  sortNodeValues($node['values']);
  sliceNodeValues($node['values'], $pool);
  if (!empty($node['children'])) {
    foreach ($node['children'] as &$child) {
      processTree($child, $pool);
    }
  }
}

/**
 * @param array $values
 */
function sortNodeValues(array &$values) {
  $length = count($values);
  for ($i = 0; $i < $length; $i++) {
    for ($j = $i + 1; $j < $length; $j++) {
      if ($values[$i] > $values[$j]) {
        swapValues($values, $i, $j);
      }
    }
  }
}

/**
 * @param array $values
 * @param int $i
 * @param int $j
 */
function swapValues(array &$values, int $i, int $j) {
  $temp = $values[$i];
  $values[$i] = $values[$j];
  $values[$j] = $temp;
}

/**
 * @param array $values
 * @param array $pool
 */
function sliceNodeValues(array &$values, array &$pool) {
  $length = count($values);
  $sum = 0;
  for ($i = 0; $i < $length; $i++) {
    $sum += $values[$i];
    if (($diff = $sum <=> W) >= 0) {
      $pool += array_splice($values, $i + !$diff);
      break;
    }
  }
}

/**
 * @param array $tree
 * @param int $shift
 */
function showTree(array $tree, int $shift = 0) {
  showNodeValues($tree['values'], $shift);
  if (!empty($tree['children'])) {
    $shift += SHIFT_INCREASE;
    foreach ($tree['children'] as $child) {
      showTree($child, $shift);
    }
  }
}

/**
 * @param array $values
 * @param int $shift
 */
function showNodeValues(array $values, int $shift) {
  showShift($shift);
  echo implode(' ', $values);
  echo '<br/>';
}

/**
 * @param int $shift
 */
function showShift(int $shift) {
  for ($i = 0; $i < $shift; $i++) {
    echo '&nbsp;';
  }
}

const W = 12;
const SHIFT_INCREASE = 6;

$sampleTree = [
  'values' => [1, 7, 6, 4, 5], //
  'children' => [
    [
      'values' => [1, 3, 7, 2],
      'children' => [
        [
          'values' => [4, 3, 5, 6],
        ],
        [
          'values' => [2, 8, 1, 4],
        ],
      ],
    ],
    [
      'values' => [4, 2, 5, 1],
      'children' => [
        [
          'values' => [2, 8, 0, 2],
        ],
        [
          'values' => [3, 8, 5, 2],
        ],
      ]
    ],
  ]
];

$pool = [];
processTree($sampleTree, $pool);
showTree($sampleTree);

echo '<br/><br/><br/>' . 'Values discarded: ' . implode(' ', $pool);