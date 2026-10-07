const express = require('express');
const cors = require('cors');
const app = express();
const PORT = 5000;

app.use(cors());
app.use(express.json());

let mockGymnasts = [
    {
        id: 1,
        full_name: "Simone Biles",
        membership_id: "GYMB-101",
        email: "simone@gymnastics.local",
        contact_no: "0829876543",
        dob: "1997-03-14",
        training_program: "Advanced",
        enrollment_date: "2026-01-10",
        status: "ACTIVE"
    },
    {
        id: 2,
        full_name: "Arthur Nory",
        membership_id: "GYMB-102",
        email: "arthur@gymnastics.local",
        contact_no: "0834567890",
        dob: "1993-09-18",
        training_program: "Intermediate",
        enrollment_date: "2026-02-15",
        status: "ON_HOLD"
    }
];

app.get('/api/gymnasts', (req, res) => {
    res.json(mockGymnasts);
});

app.get('/api/gymnasts/:id', (req, res) => {
    const gymnast = mockGymnasts.find(g => g.membership_id === req.params.id);
    if (!gymnast) return res.status(404).json({ message: "Gymnast not found" });
    res.json(gymnast);
});

app.delete('/api/gymnasts/:id', (req, res) => {
    const initialLen = mockGymnasts.length;
    mockGymnasts = mockGymnasts.filter(g => g.membership_id !== req.params.id);
    if (mockGymnasts.length === initialLen) {
        return res.status(404).json({ message: "Gymnast not found" });
    }
    res.json({ message: `Gymnast ${req.params.id} deleted successfully.` });
});

app.listen(PORT, () => {
    console.log(`Mock API running on http://localhost:${PORT}`);
});
