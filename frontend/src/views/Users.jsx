import { useEffect, useState } from "react";
import { useStateContext } from "../contexts/ContextProvider";
import axiosClient from "../axios-client";
import { Link, Navigate } from "react-router-dom";

export default function Users () {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(false);
    const {user, setNotification} = useStateContext();

    if (user.role !== 'admin') {
        return <Navigate to={"/holiday-plans"} />;
    }

    useEffect(() => {
        getUsers();
    }, []);

    const onDelete = (u) => {
       if (!window.confirm('Are you sure you want to delete this user ?')) {
        return
       }
       axiosClient.delete(`/users/${u.id}`)
        .then(() => {
            setNotification("User was successfully deleted")
            getUsers()
        })
    }

    const getUsers = () => {

        setLoading(true);
        axiosClient.get('/users')
        .then(({data}) => {
            setLoading(false);
            setUsers(data.data)
        })
        .catch(() => {
            setLoading(false);
        })
    }
    return (
        <div>
            <div style={{display: 'flex', justifyContent: 'space-between', alignItems: 'center'}}>
                <h1>Users</h1>
                <Link className="btn-add" to="/users/new"> Add New</Link>
            </div>
            <div className="card animated fadeInDown">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Create Data</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    {loading && <tbody>
                        <tr>
                            <td colSpan="5" className="text-center">
                                Loading...
                            </td>
                        </tr>
                    </tbody>
                    }
                    {!loading && <tbody>
                        {users.map(u => (
                            <tr key={u.id}>
                                <td>{u.id}</td>
                                <td>{u.name}</td>
                                <td>{u.email}</td>
                                <td>{u.role}</td>
                                <td>{u.created_at}</td>
                                <td>
                                    <Link className="btn-edit" to={'/users/'+u.id}>Edit</Link>
                                    &nbsp;
                                    <button onClick={ev => onDelete(u)} className="btn-delete">Delete</button>
                                </td>
                            </tr>
                        ))}
                    </tbody>}
                </table>
            </div>
        </div>
    )
}