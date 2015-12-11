<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2015 Woody Gilk <@shadowhand>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace StaticReview\Review\Message;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractMessageReview;
use StaticReview\Review\ReviewableInterface;

/**
 * Rule 5: Use the imperative mood in the subject line.
 *
 * <http://chris.beams.io/posts/git-commit/#imperative>
 *
 * [Word list][1] taken from [m1foley/fit-commit][2] by Mike Foley.
 *
 * [1]: http://git.io/vnzxb
 * [2]: https://github.com/m1foley/fit-commit/
 */
class SubjectImperativeReview extends AbstractMessageReview
{
    /**
     * @var array Words in the wrong tense.
     */
    protected $incorrect = [
        // Taken from m1foley/fit-commit
        // Copyright (c) 2015 Mike Foley
        'adds',       'adding',       'added',
        'allows',     'allowing',     'allowed',
        'amends',     'amending',     'amended',
        'bumps',      'bumping',      'bumped',
        'calculates', 'calculating',  'calculated',
        'changes',    'changing',     'changed',
        'cleans',     'cleaning',     'cleaned',
        'commits',    'committing',   'committed',
        'corrects',   'correcting',   'corrected',
        'creates',    'creating',     'created',
        'darkens',    'darkening',    'darkened',
        'disables',   'disabling',    'disabled',
        'displays',   'displaying',   'displayed',
        'drys',       'drying',       'dryed',
        'ends',       'ending',       'ended',
        'enforces',   'enforcing',    'enforced',
        'enqueues',   'enqueuing',    'enqueued',
        'extracts',   'extracting',   'extracted',
        'finishes',   'finishing',    'finished',
        'fixes',      'fixing',       'fixed',
        'formats',    'formatting',   'formatted',
        'guards',     'guarding',     'guarded',
        'handles',    'handling',     'handled',
        'hides',      'hiding',       'hid',
        'increases',  'increasing',   'increased',
        'ignores',    'ignoring',     'ignored',
        'implements', 'implementing', 'implemented',
        'improves',   'improving',    'improved',
        'keeps',      'keeping',      'kept',
        'kills',      'killing',      'killed',
        'makes',      'making',       'made',
        'merges',     'merging',      'merged',
        'moves',      'moving',       'moved',
        'permits',    'permitting',   'permitted',
        'prevents',   'preventing',   'prevented',
        'pushes',     'pushing',      'pushed',
        'rebases',    'rebasing',     'rebased',
        'refactors',  'refactoring',  'refactored',
        'removes',    'removing',     'removed',
        'renames',    'renaming',     'renamed',
        'reorders',   'reordering',   'reordered',
        'requires',   'requiring',    'required',
        'restores',   'restoring',    'restored',
        'sends',      'sending',      'sent',
        'sets',       'setting',
        'separates',  'separating',   'separated',
        'shows',      'showing',      'showed',
        'skips',      'skipping',     'skipped',
        'sorts',      'sorting',
        'speeds',     'speeding',     'sped',
        'starts',     'starting',     'started',
        'supports',   'supporting',   'supported',
        'takes',      'taking',       'took',
        'tests',      'testing',      'tested',
        'truncates',  'truncating',   'truncated',
        'updates',    'updating',     'updated',
        'uses',       'using',        'used',
    ];

    public function review(ReporterInterface $reporter, ReviewableInterface $commit)
    {
        $regex = '/^(?:' . implode('|', $this->incorrect) . ')\b/i';
        if (preg_match($regex, $commit->getSubject())) {
            $message = 'Subject line must use imperative present tense';
            $reporter->error($message, $this, $commit);
        }
    }
}
