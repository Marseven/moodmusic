import {ReactElement} from 'react';
import {Navigate, Outlet} from 'react-router-dom';
import {useAuth} from '../use-auth';

interface Props {
  children?: ReactElement;
  permission?: string;
}
export function AuthRoute({children, permission}: Props) {
  const {isLoggedIn, hasPermission} = useAuth();
  const redirectTo = '/login';
  if (!isLoggedIn || (permission && !hasPermission(permission))) {
    return <Navigate to={redirectTo} replace />;
  }
  return children || <Outlet />;
}
