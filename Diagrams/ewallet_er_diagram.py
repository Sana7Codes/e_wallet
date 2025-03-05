from graphviz import Digraph

er = Digraph('ER_Diagram')

# Fix missing closing quotes and proper multiline labels
er.node("Users", "Users\n(PK) id\nname\nemail\nphone\npassword_hash\nbirthdate\ncreated_at", shape="box")
er.node("Wallets", "Wallets\n(PK) id\n(FK) user_id\nbalance\ncurrency\ncreated_at", shape="box")
er.node("Transactions",
        "Transactions\n(PK) id\n(FK) user_id\namount\ntype\nstatus\ncreated_at", shape="box")
er.node("KYC_Verification",
        "KYC_Verification\n(PK) id\n(FK) user_id\ndocument_type\ndocument_path\nstatus\nsubmitted_at", shape="box")
er.node("System_Logs",
        "System_Logs\n(PK) id\n(FK) user_id\naction\ntimestamp", shape="box")

# Define relationships
er.edge("Users", "Wallets", label="1 to 1", style="dashed")
er.edge("Users", "Transactions", label="1 to many", style="dashed")
er.edge("Users", "KYC_Verification", label="1 to 1", style="dashed")
er.edge("Users", "System_Logs", label="1 to many", style="dashed")

# Render and save the ER diagram
er.render("ewallet_er_diagram", format="png", cleanup=True)

print("✅ ER Diagram successfully generated as ewallet_er_diagram.png")
