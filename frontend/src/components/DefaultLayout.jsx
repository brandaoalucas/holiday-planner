import { Link, Navigate, Outlet } from "react-router-dom";
import { useStateContext } from "../contexts/ContextProvider";
import { useEffect, useState } from "react";
import axiosClient from "../axios-client";

export default function DefaultLayout () {
    const {user, token, notification, setUser, setToken} = useStateContext();
    const [isAdmin, setIsAdmin] = useState(false);

    useEffect(() => {
        if (token) {
            axiosClient.get('/user')
                .then(({ data }) => {
                    setUser(data.user);
                    if (data.user.role === 'admin') {
                        setIsAdmin(true);
                    }
                })
                .catch(() => {
                    setToken(null);
                    setUser({});
                });
        }
    }, [token]);

    const onLogout = (ev) => {
        ev.preventDefault();
        axiosClient.post('/logout')
            .then(() => {
                setUser({});
                setToken(null);
            });
    };

    if (!token) {
        return <Navigate to="/login" />;
    }

    return (
        <div id="defaultLayout" class="d-flex">
            <aside >
                <Link to="/holiday-plans">Holiday Plans</Link>
                {isAdmin &&
                    <Link to="/users"> Users</Link>}
            </aside>
            <div className="content">
                <header>
                    <div>
                        Holiday Planner
                    </div>
                    <div>
                        {user.name}
                        <a href="#" onClick={onLogout} className="btn-logout">Logout</a>
                    </div>
                </header>
                <main>
                    <Outlet />
                </main>
            </div>
            {notification &&
                <div className="notification">
                    {notification}
                </div>
            }
        </div>
    )
}
