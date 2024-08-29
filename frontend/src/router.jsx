import {createBrowserRouter, Navigate} from 'react-router-dom';
import DefaultLayout from './components/DefaultLayout';
import GuestLayout from './components/GuestLayout';
import HolidayPlans from './views/HolidayPlans';
import HolidayPlanForm from './views/HolidayPlanForm';
import Login from './views/Login';
import Signup from './views/Signup';
import Users from './views/Users';
import UserForm from './views/UserForm';
import NotFound from './views/NotFound';

const route = createBrowserRouter([
    {
        path: '/',
        element: <DefaultLayout />,
        children: [
            {
                path: '/',
                element: <Navigate to={"/holiday-plans"} />
            },
            {
                path: '/holiday-plans',
                element: <HolidayPlans />
            },
            {
                path: '/holiday-plans/new',
                element: <HolidayPlanForm />
            },
            {
                path: '/holiday-plans/:id',
                element: <HolidayPlanForm />
            },
            {
                path: '/users',
                element: <Users />
            },
            {
                path: '/users/new',
                element: <UserForm key="userCreate"/>
            },
            {
                path: '/users/:id',
                element: <UserForm key="userUpdate"/>
            },
        ]
    },
    {
        path: '/',
        element: <GuestLayout />,
        children: [
            {
                path: '/',
                element: <Navigate to={"/login"} />
            },
            {
                path: '/login',
                element: <Login />
            },
            {
                path: '/signup',
                element: <Signup />
            }
        ]
    },
    {
        path: '*',
        element: <NotFound />
    }
]);

export default route;
