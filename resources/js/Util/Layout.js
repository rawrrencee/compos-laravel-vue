export const isCurrent = (menuItem, currentRoute, exactMatch = false) => {
    return !!currentRoute && exactMatch
        ? currentRoute === menuItem.href
        : currentRoute.includes(menuItem.href);
};
