import React, { useState, useEffect } from 'react';
import ProfileCard from './ProfileCard';

export default function GymnastDashboard() {
  const [gymnasts, setGymnasts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedGymnast, setSelectedGymnast] = useState(null);

  useEffect(() => {
    fetch('http://localhost:5000/api/gymnasts')
      .then((res) => res.json())
      .then((data) => {
        setGymnasts(data);
        setLoading(false);
      })
      .catch((err) => {
        console.error('Fetch error:', err);
        setLoading(false);
      });
  }, []);

  const handleDelete = (membershipId) => {
    if (window.confirm(`Are you sure you want to delete ${membershipId}?`)) {
      fetch(`http://localhost:5000/api/gymnasts/${membershipId}`, {
        method: 'DELETE',
      })
        .then((res) => {
          if (res.ok) {
            setGymnasts((prev) => prev.filter((g) => g.membership_id !== membershipId));
            if (selectedGymnast?.membership_id === membershipId) {
              setSelectedGymnast(null);
            }
          }
        })
        .catch((err) => console.error('Delete error:', err));
    }
  };

  if (loading) return <div style={{ padding: '24px', color: '#fff' }}>Loading gymnasts from mock API...</div>;

  return (
    <div style={{ padding: '24px', fontFamily: 'Arial, sans-serif', color: '#fff' }}>
      <h2>React Gymnast Dashboard (Functional Component)</h2>
      <table style={{ width: '100%', borderCollapse: 'collapse', marginTop: '16px', color: '#fff' }} border="1" cellPadding="8">
        <thead>
          <tr style={{ background: '#333', textAlign: 'left' }}>
            <th>Membership ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Program</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {gymnasts.map((g) => (
            <tr key={g.membership_id}>
              <td>{g.membership_id}</td>
              <td>{g.full_name}</td>
              <td>{g.email}</td>
              <td>{g.training_program}</td>
              <td>{g.status}</td>
              <td>
                <button 
                  onClick={() => setSelectedGymnast(g)} 
                  style={{ marginRight: '8px', cursor: 'pointer', padding: '4px 8px' }}
                >
                  View Card
                </button>
                <button 
                  onClick={() => handleDelete(g.membership_id)} 
                  style={{ cursor: 'pointer', color: 'red', padding: '4px 8px' }}
                >
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {selectedGymnast && (
        <ProfileCard gymnast={selectedGymnast} onDelete={handleDelete} />
      )}
    </div>
  );
}
