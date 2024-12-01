<?php

namespace TreptowKolleg;

enum Path
{
    /**
     * entry point root dir
     */
    case LOCAL_DIR;

    /**
     * local user dir
     */
    case USER_DIR;

    /**
     * shared public dir
     */
    case PUBLIC_DIR;

    /**
     * custom dir
     */
    case CUSTOM_DIR;
}