function generateProfileReport() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const name = document.getElementById('mName').innerText;
    const mId = document.getElementById('mId').innerText;
    const email = document.getElementById('mEmail').innerText;
    const dob = document.getElementById('mDob').innerText;
    const program = document.getElementById('mProgram').innerText;
    const enrollmentDate = document.getElementById('mDate').innerText;

    doc.setFontSize(18);
    doc.text('Gymnastics Academy - Profile Summary', 20, 20);

    doc.setFontSize(12);
    doc.text(`Membership ID: ${mId}`, 20, 40);
    doc.text(`Full Name: ${name}`, 20, 50);
    doc.text(`Email: ${email}`, 20, 60);
    doc.text(`Date of Birth: ${dob}`, 20, 70);
    doc.text(`Program: ${program}`, 20, 80);
    doc.text(`Enrollment Date: ${enrollmentDate}`, 20, 90);

    doc.save(`${mId}_Profile_Summary.pdf`);
}