import { useEffect, useState } from "react";
import axiosClient from "../axios-client";
import { Link } from "react-router-dom";
import { useStateContext } from "../contexts/ContextProvider";

export default function HolidayPlans () {
    const [plans, setPlans] = useState([]);
    const {setNotification} = useStateContext();
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        getPlans();
    }, []);

    const onDelete = (p) => {
        const confirm = window.confirm('Are you sure you want to delete this plan ?')
        if (!confirm) {
         return
        }

        axiosClient.delete(`/holiday-plans/${p.id}`)
         .then(() => {
            setNotification("Plan was successfully deleted")
             getPlans()
         })
     }

     const handleDownload = async (planId) => {
        try {
            const response = await axiosClient.get(`/holiday-plan/${planId}/pdf`, {
                responseType: 'blob',
            });

            if (response.status === 200) {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `holiday_plan_${planId}.pdf`);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);

                setNotification("PDF downloaded successfully");
            } else {
                setNotification("Failed to download the PDF");
            }
        } catch (error) {
            console.error('Error downloading the PDF:', error);
            setNotification("Error occurred during the download");
        }
    };

    const getPlans = () => {

        setLoading(true);

        axiosClient.get('/holiday-plans')
        .then(({data}) => {
            setLoading(false);
            setPlans(data.data)
        })
        .catch(() => {
            setLoading(false);
        })
    }
    return (
        <div>
            <div style={{display: 'flex', justifyContent: 'space-between', alignItems: 'center'}}>
                <h1>Holiday Plans</h1>
                <Link className="btn-add" to="/holiday-plans/new"> Add New</Link>
            </div>
            <div className="card animated fadeInDown">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Participants</th>
                            <th>Create Data</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    {loading && <tbody>
                        <tr>
                            <td colSpan="7" className="text-center">
                                Loading...
                            </td>
                        </tr>
                    </tbody>
                    }
                    {!loading && <tbody>
                        {plans && plans.map(p => (
                            <tr key={p.id}>
                                <td>{p.id}</td>
                                <td>{p.title}</td>
                                <td>{p.description}</td>
                                <td>{p.date}</td>
                                <td>{p.location}</td>
                                <td>{p.participants.join(', ')}</td>
                                <td>{p.created_at}</td>
                                <td className="actions-cell">
                                    <Link className="btn-edit" to={'/holiday-plans/'+p.id}>Edit</Link>
                                    &nbsp;
                                    <button onClick={ev => onDelete(p)} className="btn-delete">delete</button>
                                    &nbsp;
                                    <button onClick={() => handleDownload(p.id)} className="btn-download">Download</button>
                                </td>
                            </tr>
                        ))}
                    </tbody>}
                </table>
            </div>
        </div>
    );
}