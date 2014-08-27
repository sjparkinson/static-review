<?php
/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE.md
 */

namespace StaticReview\VersionControl;

class VersionControlFactory
{
    const SYSTEM_GIT = 'git';

    /**
     * Returns a new instance of a VersionControlInterface implmenting class.
     *
     * @param  string                  $versionControlType
     * @return VersionControlInterface
     */
    public static function build($versionControlType)
    {
        $namespace = __NAMESPACE__ . '\\';

        $versionControl = $namespace . ucwords($versionControlType) . 'VersionControl';

        return new $versionControl();
    }
}
