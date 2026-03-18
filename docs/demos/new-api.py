from flask import Flask, jsonify, request
from contextlib import contextmanager
from dataclasses import dataclass
import psycopg2
from psycopg2.extras import RealDictCursor

app = Flask(__name__)

@dataclass
class User:
    id: int
    name: str
    email: str
    role: str = "member"

@contextmanager
def get_db():
    conn = psycopg2.connect("dbname=demo user=admin", cursor_factory=RealDictCursor)
    try:
        yield conn
    finally:
        conn.close()

@app.route("/users")
def list_users():
    role = request.args.get("role")
    with get_db() as conn:
        cur = conn.cursor()
        if role:
            cur.execute("SELECT id, name, email, role FROM users WHERE role = %s", (role,))
        else:
            cur.execute("SELECT id, name, email, role FROM users")
        return jsonify([User(**row).__dict__ for row in cur.fetchall()])

@app.route("/users/<int:user_id>")
def get_user(user_id):
    with get_db() as conn:
        cur = conn.cursor()
        cur.execute("SELECT id, name, email, role FROM users WHERE id = %s", (user_id,))
        row = cur.fetchone()
        if row:
            return jsonify(User(**row).__dict__)
        return jsonify({"error": "User not found", "user_id": user_id}), 404

@app.route("/users", methods=["POST"])
def create_user():
    data = request.get_json()
    with get_db() as conn:
        cur = conn.cursor()
        cur.execute(
            "INSERT INTO users (name, email, role) VALUES (%s, %s, %s) RETURNING id, name, email, role",
            (data["name"], data["email"], data.get("role", "member")),
        )
        conn.commit()
        return jsonify(User(**cur.fetchone()).__dict__), 201
