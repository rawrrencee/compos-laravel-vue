export const isCurrent = (menuItem, currentRoute, exactMatch = false) => {
  console.log('currentRoute', currentRoute, 'menuItem.href', menuItem.href);
  return !!currentRoute && exactMatch ? currentRoute === menuItem.href : currentRoute.includes(menuItem.href);
};
