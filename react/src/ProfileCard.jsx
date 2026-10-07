import React from 'react';

export default function ProfileCard({ gymnast, onDelete }) {
  if (!gymnast) return null;

  return (
    <div style={{
      marginTop: '20px',
      padding: '16px',
      background: '#fff',
      borderRadius: '8px',
      boxShadow: '0 2px 8px rgba(0,0,0,0.15)',
      maxWidth: '450px',
      color: '#333'
    }}>
      <h3>{gymnast.full_name} ({gymnast.membership_id})</h3>
      <p><strong>Email:</strong> {gymnast.email}</p>
      <p><strong>Program:</strong> {gymnast.training_program}</p>
      <p><strong>Status:</strong> <span style={{ fontWeight: 'bold' }}>{gymnast.status}</span></p>
      <button 
        onClick={() => onDelete(gymnast.membership_id)}
        style={{
          backgroundColor: '#d32f2f',
          color: '#fff',
          border: 'none',
          padding: '8px 14px',
          borderRadius: '4px',
          cursor: 'pointer'
        }}
      >
        Delete Gymnast
      </button>
    </div>
  );
}
