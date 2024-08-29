import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axiosClient from "../axios-client";
import { useStateContext } from "../contexts/ContextProvider";

export default function holidayPlanForm() {
    const {id} = useParams();
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false);
    const {setNotification} = useStateContext();
    const [errors, setErrors] = useState(null);
    const [plan, setPlan] = useState({
        id: null,
        user_id: null,
        title: "",
        description: "",
        date: "",
        location: "",
        participants: [],
    });

    useEffect(() => {
        if (id) {
            setLoading(true);
            axiosClient.get(`/holiday-plans/${id}/show`)
                .then(({ data }) => {
                    setLoading(false);
                    const participantsString = data.data.participants.join(", ");
                    setPlan({ ...data.data, participants: participantsString });
                })
                .catch(() => {
                    setLoading(false);
                });
        }
    }, [id]);

    const onSubmit = (ev) => {
        ev.preventDefault();

        const participantsArray = 
            typeof plan.participants === 'string' && plan.participants.includes(',')
                ? plan.participants.split(',').map(name => name.trim())
                : typeof plan.participants === 'string' && plan.participants.length > 0
                ? [plan.participants.trim()]
                : [];
                
        const planToSubmit = { ...plan, participants: participantsArray };

        if (plan.id) {
            axiosClient.put(`/holiday-plans/${plan.id}`, planToSubmit)
                .then(() => {
                    setNotification('Plan was successfully updated.')
                    navigate('/holiday-plans');
                })
                .catch(err => {
                    const response = err.response;
                    if (response && response.status === 422) {
                        setErrors(response.data.errors);
                    }
                });
        } else {
            axiosClient.post(`/holiday-plans`, planToSubmit)
                .then(() => {
                    setNotification('Plan was successfully updated.')
                    navigate('/holiday-plans');
                })
                .catch(err => {
                    const response = err.response;
                    if (response && response.status === 422) {
                        setErrors(response.data.errors);
                    }
                });
        }
    };

    return (
        <>
        {plan.id && <h1>Update Plan: {plan.name}</h1>}
        {!plan.id && <h1>New Plan:</h1>}
        <div className="card animated fadeInDown">
            {loading && (
                <div className="text-center">Loading...</div>
            )}
            {errors && <div className="alert">
                {Object.keys(errors).map(key => (
                    <p key={key}>{errors[key][0]}</p>
                ))}
            </div>
            }
            {!loading &&
                <form onSubmit={onSubmit}>
                    <input value={plan.title} onChange={ev => setPlan({...plan, title: ev.target.value})} placeholder="Title" />
                    <input value={plan.description} onChange={ev => setPlan({...plan, description: ev.target.value})} placeholder="Description" />
                    <input type="date" value={plan.date} onChange={ev => setPlan({...plan, date: ev.target.value})} placeholder="Date" />
                    <input value={plan.location} onChange={ev => setPlan({...plan, location: ev.target.value})} placeholder="Location" />
                    <input value={plan.participants} onChange={ev => setPlan({...plan, participants: ev.target.value})} placeholder="Enter participants, separated by commas" />
                    <button className="btn">Save</button>
                </form>
            }
        </div>
    </>
    )
}